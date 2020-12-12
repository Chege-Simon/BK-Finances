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
    public $isOpen;
    public $the_user;
    public $role;

    protected $rules = [
        'role' => 'required'
    ];

    protected $listeners = ['deleteUser'];

    public function openModal($id){
        $this->the_user = User::find($id);
        $this->role = $this->the_user->role;
        $this->isOpen = true;
    }

    public function closeModal(){
        $this->isOpen = false;
    }

    public function editUserRole(){
        $this->validate();

        $this->the_user->role =  $this->role;

        $this->the_user->save();
        
        $this->closeModal();
        $this->emit('swal:alert', [
            'type'    => 'success',
            'title'   => 'User Role Edited Successfully',
            'timeout' => 10000
        ]);
    }

    public function confirmDelete($id){
        $this->the_user = User::find($id);
        $this->emit("swal:confirm", [
            'type'        => 'danger',
            'title'       => 'Confirm Deletion of User',
            'text'        => "Full Name: <b>".$this->the_user->first_name." ".$this->the_user->middle_name." ".$this->the_user->last_name."</b><br>Phone Number: <b>".$this->the_user->phone_number."</b><br>Email: <b>".$this->the_user->email."</b></b><br><hr><br><b>Are you sure you want to delete this user?</b><br><h5><b>Caution! This action is unreversable</b></h5>",
            'confirmText' => 'Yes, Delete!',
            'method'      => 'deleteUser',
            'params'      => [], // optional, send params to success confirmation
            'callback'    => '', // optional, fire event if no confirmed
        ]);
    }


    public function deleteUser(){
        $this->the_user->delete();
        $this->emit('swal:alert', [
            'type'    => 'success',
            'title'   => 'User Deleted Successfully',
            'timeout' => 10000
        ]);
    }

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
            'users'=> $this->resultData()
        ]);
    }
}
