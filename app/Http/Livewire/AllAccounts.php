<?php

namespace App\Http\Livewire;

use App\Models\Account;
use App\Models\Organisation;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class AllAccounts extends Component
{
    use withPagination;

    private $headers;
    public $sortColumn = 'created_at';
    public $sortDirection = 'asc';
    public $searchTerm = '';

    private function headerConfig()
    {
        return [
            'user_account_number' => 'Organisation Name',
            'organisation' => [
                'label' => 'Organisation',
                'func' => function($value){
                    return $value->organisation_name;
                }
            ],
            'user' => [
                'label' => 'Owner\'s Phone Number',
                'func' => function($value){
                    return $value->phone_number;
                }
            ],

            'created_at' => 'Date/Time Registered',
        ];
    }

    public function mount()
    {
        $this->headers = $this->headerConfig();
    }
    public function hydrate(){
        $this->headers = $this->headerConfig();
    }

    public function sort($column)
    {
        if($column == 'organisation'){
            $this->sortColumn = "organisation_id";
        }else if($column == 'user'){
            $this->sortColumn = 'user_id';
        }else{
            $this->sortColumn = $column;
        }
        $this->sortDirection = $this->sortDirection == 'asc' ? 'desc' : 'asc';
    }
    private function switcher(){

    }
    public function searchOrgTerm(){
        $org =  Organisation::where('organisation_name', 'like', '%'.$this->searchTerm.'%')->first();
        $id = '';
        if($org != null){
            $id = $org->id;
        }else{
            $id = " ";
        }
        return $id;
    }

    private function searchUserTerm(){
        $user = User::where('phone_number', 'like', '%'.$this->searchTerm.'%')->first();
        $id = "";
        if($user != null){
            $id = $user->id;
        }else{
            $id = " ";
        }
        return $id;
    }

    private function resultData()
    {
        return Account::where(function ($query) {
            $query->where('user_id', '!=', '');
            if ($this->searchTerm != "") {
                $query->where('user_account_number', 'like', '%' . $this->searchTerm . '%');
                $query->orWhere('organisation_id', 'like', '%' .$this->searchOrgTerm().'%');
                $query->orWhere('user_id', 'like', '%' .$this->searchUserTerm().'%');
                $query->orWhere('created_at', 'like', '%' . $this->searchTerm . '%');
            }
        })
            ->with('user')
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.all-accounts', [
            'accounts' => $this->resultData(),
            'headers' => $this->headers
        ])
            ->layout('layouts.app', ['header' => 'All Accounts']);
    }
}


