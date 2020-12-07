<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;

class MyTransactions extends Component
{
    public $user_id;

    public function mount($id){
        $user = User::find($id);
        $this->user_id = $user->id;
    }

    public function render()
    {
        return view('livewire.my-transactions')
            ->layout('layouts.app',['header' => 'My Transactions']);
    }
}
