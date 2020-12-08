<?php

namespace App\Http\Livewire;

use App\Models\User;
use App\Models\Account;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\Organisation;

class MyAccounts extends Component
{

    public function getAccounts(){
        return Account::where('user_id','=',Auth::user()->id)->get();

    }

    public function render()
    {
        return view('livewire.my-accounts',[
            'accounts' => $this->getAccounts()
        ])
            ->layout('layouts.app', ['header' => 'My Accounts']);
    }
}
