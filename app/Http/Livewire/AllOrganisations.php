<?php

namespace App\Http\Livewire;

use App\Models\Organisation;
use Livewire\Component;
use Livewire\WithPagination;

class AllOrganisations extends Component
{
    use withPagination;

    public $headers;
    public $sortColumn = 'created_at';
    public $sortDirection = 'asc';
    public $searchTerm = '';

    private function headerConfig()
    {
        return [
            'organisation_name' => 'Organisation Name',
            'organisation_account_number' => 'Account Number',
            'created_at' => 'Date/Time Registered',
        ];
    }

    public function mount()
    {
        $this->headers = $this->headerConfig();
    }

    public function sort($column)
    {
        $this->sortColumn = $column;
        $this->sortDirection = $this->sortDirection == 'asc' ? 'desc' : 'asc';
    }
    private function resultData()
    {
        return Organisation::where(function ($query) {
            $query->where('organisation_name', '!=', '');
            if ($this->searchTerm != "") {
                $query->where('organisation_name', 'like', '%' . $this->searchTerm . '%');
                $query->orWhere('organisation_account_number', 'like', '%' . $this->searchTerm . '%');
                $query->orWhere('created_at', 'like', '%' . $this->searchTerm . '%');
            }
        })
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->paginate(10);
    }
    public function render()
    {
        return view('livewire.all-organisations', [
            'organisations' => $this->resultData()
        ])
            ->layout('layouts.app', ['header' => 'All Organisations']);
    }
}
