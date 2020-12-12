<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use App\Models\Account;
use App\Models\Organisation;
use App\Models\Transaction;

class Action extends Component
{
    public $withdraw_amount;
    public $deposit_amount;
    public $expected_withdraw_amount;
    public $expected_deposit_amount;
    public $conversion_rate_to_usd = 111.212;
    public $conversion_rate_to_kes = 0.009;
    public $account_number;
    public $organisation;
    public $validated_data;
    public $isOpen = false;
    public $organisations;
    public $accounts;
    public $account_id;
    public $open_confirm = false;
    public $the_account;
    private $results;
    public $processingMsg;
    public $STKPushRequestStatus;
    public $code;
    public $CheckoutID;


    protected $rules = [
        'account_number' => 'required|min:5',
        'organisation' => 'required',
    ];

    protected $listeners = ['deposit','canceledTransaction'];

    public function confirmDeposit(){
        if($this->account_id > 0){
            // show alert
            $this->emit("swal:confirm", [
                'type'        => 'primary',
                'title'       => 'Confirm Details',
                'text'        => "Account: <b>".$this->the_account->user_account_number."</b><br>Organisation: <b>".$this->the_account->organisation->organisation_name."</b><br>Amount: <b>".$this->deposit_amount."</b><br><hr><br><b>Enter Mpesa pin on your phone</b>",
                'confirmText' => 'Yes, Deposit!',
                'method'      => 'deposit',
                'params'      => [], // optional, send params to success confirmation
                'callback'    => 'canceledTransaction', // optional, fire event if no confirmed
            ]);
        }else{
            $this->emit('swal:alert', [
                'type'    => 'warning',
                'title'   => 'Select Account or Register new to select',
                'timeout' => 10000
            ]);
        }
    }

    public function canceledTransaction()
    {
        $this->emit('swal:alert', [
            'type'    => 'warning',
            'title'   => 'Transaction cancelled by User',
            'timeout' => 10000
        ]);
    }

    public function recordTransaction(){
        Transaction::create([
            'account_id' => $this->the_account->id,
            'amount' => $this->deposit_amount,
            'status' => "Deposit: In transaction",
        ]);
    }

    public function deposit(){

        $mpesa= new \Safaricom\Mpesa\Mpesa();

        $this->CheckoutID = $this->requestSTK();

        $this->checkSTKStatus($this->CheckoutID);
        $timer = 45;
        sleep($timer);

        $this->checkSTKStatus($this->CheckoutID);

        $this->emit('swal:alert', [
            'type'    => 'success',
            'title'   => $this->processingMsg,
            'timeout' => 10000
        ]);
        $this->emit('swal:alert', [
            'type'    => 'success',
            'title'   => "Recording transaction",
            'timeout' => 10000
        ]);
    }

    public function checkSTKStatus($CheckoutRequestID){
        $mpesa= new \Safaricom\Mpesa\Mpesa();
        $BusinessShortCode = "174379";
        $LipaNaMpesaPasskey = "bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919";
        $timestamp='20'.date("ymdhis");
        $password=base64_encode($BusinessShortCode.$LipaNaMpesaPasskey.$timestamp);

        $STKPushRequestStatus=$mpesa->STKPushQuery($CheckoutRequestID,$BusinessShortCode,$password,$timestamp);

        $json = json_decode($STKPushRequestStatus);
        if(isset($json->ResultDesc)){
            $this->processingMsg = $json->ResultDesc;
            $this->code = $json->ResultCode;
            $this->recordTransaction();
        }elseif($json == ''){
            $this->processingMsg = 'Connection error';
        }else{
            $this->processingMsg = 'Connection error';
        }
        $this->results = $STKPushRequestStatus;
    }

    public function requestSTK (){
        $mpesa= new \Safaricom\Mpesa\Mpesa();
        $message = '';
        $process_success = false;
        $organisation = $this->the_account->organisation->organisation_name;


        $number = auth()->user()->phone_number;
        $phone_number = substr($number,1);

        $BusinessShortCode = "174379";
        $LipaNaMpesaPasskey = "bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919";
        $TransactionType = "CustomerPayBillOnline";
        $Amount = "1";//$request->deposit_amount;
        $PartyA = $phone_number;
        $PartyB = "174379";
        $PhoneNumber = $phone_number;
        $CallBackURL = "http://fa202c33b745.ngrok.io/mpesa";
        $AccountReference = "BK-Finances";
        $TransactionDesc = "Depositing to ".$organisation;
        $Remarks = "success";
        $stkPushSimulation=$mpesa->STKPushSimulation($BusinessShortCode, $LipaNaMpesaPasskey, $TransactionType, $Amount, $PartyA, $PartyB, $PhoneNumber, $CallBackURL, $AccountReference, $TransactionDesc, $Remarks);

        $json = json_decode($stkPushSimulation);
        $CheckoutRequestID = $json->CheckoutRequestID;
        return $CheckoutRequestID;

    }

    public function closeConfirm(){
        $this->open_confirm = false;
    }


    public function fetchOrganisations(){
        $this->organisations = Organisation::all();
    }

    public function fetchAccounts(){
        $this->accounts = Account::where('user_id','=',auth()->user()->id)->get();
    }

    public function openModal(){
        $this->isOpen = true;
    }

    public function closeModal(){
        $this->isOpen = false;
    }

    public function updated(){
        if($this->account_id){
            $this->the_account = Account::find($this->account_id);
        }
        if($this->withdraw_amount >= 10){
            $this->expected_withdraw_amount = round($this->withdraw_amount * 0.98 / $this->conversion_rate_to_kes,2);
        }else{
            $this->expected_withdraw_amount = 0;
        }
        if($this->deposit_amount >= 1000){
            $this->expected_deposit_amount = round($this->deposit_amount * 0.98 / $this->conversion_rate_to_usd,2);
        }else{
            $this->expected_deposit_amount = 0;
        }

    }
    public function addAccount(){

        $this->validate();

        Account::create([
            'user_account_number' => $this->account_number,
            'organisation_id' => $this->organisation,
            'user_id' => auth()->user()->id,
        ]);
        $this->closeModal();
    }


    public function render()
    {
        $this->fetchOrganisations();
        $this->fetchAccounts();
        return view('livewire.action',[
            'results' => $this->results,
        ]);
    }
}
