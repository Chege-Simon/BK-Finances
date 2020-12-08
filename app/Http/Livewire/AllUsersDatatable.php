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
    public $searchTerm = '';

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
        return User::where(function ($query){
            $query->where('phone_number','like','+254'.'%');
            if($this->searchTerm != ""){
                $query->where('first_name','like','%'.$this->searchTerm.'%');
                $query->orWhere('middle_name','like','%'.$this->searchTerm.'%');
                $query->orWhere('last_name','like','%'.$this->searchTerm.'%');
                $query->orWhere('email','like','%'.$this->searchTerm.'%');
                $query->orWhere('phone_number','like','%'.$this->searchTerm.'%');
                $query->orWhere('role','like','%'.$this->searchTerm.'%');
            }
        })
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.all-users-datatable',[
            'data'=> $this->resultData()
        ]);
    }
}
