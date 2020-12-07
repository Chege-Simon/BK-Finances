<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;

class AllUsersDatatable extends Component
{
    use withPagination;
    public $headers;
    public $sortColumn = 'created_at';
    public $sortDirection = 'asc';

    private function headerConfig(){
        return [
            'phone_number' => 'Phone Number',
            'first_name' => 'First Name',
            'middle_name' => 'Middle Name',
            'last_name' => 'Last Name',
            'email' => 'Email',
            'role' => 'Role',
            'created_at' => 'Created At',
        ];
    }

    public function mount(){
        $this->headers = $this->headerConfig();
    }

    public function sort($column){
        $this->sortColumn = $column;
        $this->sortDirection = $this->sortDirection== 'asc' ? 'desc':'asc';
    }

    private function resultData(){
        return User::where('first_name', '!=', '' )->orderBy($this->sortColumn, $this->sortDirection)->paginate(2);
    }

    public function render()
    {
        return view('livewire.all-users-datatable',[
            'data'=> $this->resultData()
        ]);
    }
}
