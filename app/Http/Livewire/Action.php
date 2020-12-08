<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use App\Models\Account;
use App\Models\Organisation;

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
    public $greet;


    protected $rules = [
        'account_number' => 'required|min:5',
        'organisation' => 'required',
    ];

    public function fetchOrganisations(){
        $this->organisations = Organisation::all();
    }

    public function openModal(){
        $this->isOpen = true;
    }

    public function closeModal(){
        $this->isOpen = false;
    }

    public function updated(){
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
        return view('livewire.action');
    }
}
