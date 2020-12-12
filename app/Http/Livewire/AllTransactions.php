<?php

namespace App\Http\Livewire;


use App\Models\Account;
use App\Models\Organisation;
use App\Models\User;
use App\Models\Transaction;
use Livewire\Component;
use Livewire\WithPagination;

class AllTransactions extends Component
{
    use withPagination;

    private $headers;
    public $sortColumn = 'created_at';
    public $sortDirection = 'asc';
    public $searchTerm = '';

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

    public function mount()
    {
        $this->headers = $this->headerConfig();
    }
    public function hydrate(){
        $this->headers = $this->headerConfig();
    }

    public function sort($column)
    {   if($column == 'organisation'){
        $this->sortColumn = 'account_id';
        }elseif($column == 'user_account_number'){
            $this->sortColumn = 'account_id';
        }else{
            $this->sortColumn = $column;
        }
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

    public function searchAccTerm(){
        $acc =  Account::where('user_account_number', 'like', '%'.$this->searchTerm.'%')->first();
        $id = '';
        if($acc != null){
            $id = $acc->id;
        }else{
            $id = " ";
        }
        return $id;
    }

    private function resultData()
    {
        return Transaction::where(function ($query) {
            $query->where('account_id', '!=', '');
            if ($this->searchTerm != "") {
                $query->where('account_id', 'like', '%' . $this->searchAccTerm() . '%');
                $query->orWhere('amount', 'like', '%' . $this->searchTerm . '%');
                $query->orWhere('created_at', 'like', '%' . $this->searchTerm . '%');
                $query->orWhere('status', 'like', '%' . $this->searchTerm . '%');
            }
        })
            ->with('account')
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.all-transactions',[
        'transactions' => $this->resultData(),
        'headers' => $this->headers
    ])
        ->layout('layouts.app',['header' => 'All Transactions']);
    }
}