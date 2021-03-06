<?php

namespace App\Http\Livewire;

use App\Models\Account;
use App\Models\Organisation;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
;

class MyTransactions extends Component
{
    use withPagination;

    private $headers;
    public $sortColumn = 'created_at';
    public $sortDirection = 'asc';
    public $searchTerm = '';
    private $user_id;

    private function headerConfig()
    {
        return [
            'user_account_number' => [
                'label' => 'Account Number',
                'func' => function($value){
                    return $value;
                }
            ],
            'organisation' => [
                'label' => 'Organisation',
                'func' => function($value){
                    return $value->organisation_name;
                }
            ],
            'amount' => 'Amount',
            'created_at' => 'Date/Time Registered',
            'status' => 'Transaction Status',
        ];
    }

    public function mount($id)
    {
        $this->headers = $this->headerConfig();
        $this->user_id = $id;
    }
    public function hydrate(){
        $this->headers = $this->headerConfig();
    }

    public function sort($column)
    {
        $this->sortColumn = 'created_at';
        $this->sortDirection = $this->sortDirection == 'asc' ? 'desc' : 'asc';
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

    private function resultData()
    {
        return Account::where(function ($query) {
            $query->where('user_id', '=', $this->user_id);
            if ($this->searchTerm != "") {
                $query->where('user_account_number', 'like', '%' . $this->searchTerm . '%');
                $query->orWhere('organisation_id', 'like', '%' .$this->searchOrgTerm().'%');
            }
        })
            ->with('transactions')
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.my-transactions',[
            'accounts' => $this->resultData(),
            'headers' => $this->headers
        ])
            ->layout('layouts.app',['header' => 'My Transactions']);
    }
}



