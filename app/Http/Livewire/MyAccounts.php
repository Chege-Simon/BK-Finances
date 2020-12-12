<?php

namespace App\Http\Livewire;

use App\Models\User;
use App\Models\Account;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\Organisation;

class MyAccounts extends Component
{
    public $organisations;
    public $organisation_id;
    public $account_number;
    public $account_id;
    public $the_account;
    public $isOpen;

    protected $rules = [
        'account_number' => 'required|min:5',
        'organisation_id'=> 'required'
    ];

    protected $listeners = ['deleteAccount'];

    public function getAccounts(){
        return Account::where('user_id','=',Auth::user()->id)->get();

    }

    public function openModal($id){
        $this->account_id = $id;
        $this->the_account = Account::find($this->account_id);
        $this->account_number = $this->the_account->user_account_number;
        $this->organisation_id = $this->the_account->organisation_id;
        $this->isOpen = true;
    }

    public function closeModal(){
        $this->isOpen = false;
    }

    public function editAccount(){
        $this->validate();

        $this->the_account->user_account_number =  $this->account_number;
        $this->the_account->organisation_id =  $this->organisation_id;

        $this->the_account->save();
        
        $this->closeModal();
        $this->emit('swal:alert', [
            'type'    => 'success',
            'title'   => 'Account Edited Successfully',
            'timeout' => 10000
        ]);
    }

    public function confirmDelete($id){
        $this->the_account = Account::find($id);
        $this->emit("swal:confirm", [
            'type'        => 'danger',
            'title'       => 'Confirm Deletion of Account',
            'text'        => "Account: <b>".$this->the_account->user_account_number."</b><br>Organisation: <b>".$this->the_account->organisation->organisation_name."</b></b><br><hr><br><b>Are you sure you want to delete this account?</b><br><h5><b>Caution! This action is unreversable</b></h5>",
            'confirmText' => 'Yes, Delete!',
            'method'      => 'deleteAccount',
            'params'      => [], // optional, send params to success confirmation
            'callback'    => '', // optional, fire event if no confirmed
        ]);
    }


    public function deleteAccount(){
        $this->the_account->delete();
        $this->emit('swal:alert', [
            'type'    => 'success',
            'title'   => 'Account Deleted Successfully',
            'timeout' => 10000
        ]);
    }

    public function getOrganisations(){
        $this->organisations = Organisation::all();
    }

    public function mount(){
        $this->getOrganisations();
    }

    public function render()
    {
        return view('livewire.my-accounts',[
            'accounts' => $this->getAccounts()
        ])
            ->layout('layouts.app', ['header' => 'My Accounts']);
    }
}
