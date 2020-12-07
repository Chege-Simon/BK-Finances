<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;


class AllUsers extends Component
{
    public $users = User::class;
    public function render()
    {
        return view('livewire.all-users')
            ->layout('layouts.app',['header'=> 'All Users']);
    }
}
