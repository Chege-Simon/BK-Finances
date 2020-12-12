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
    public $organisation_name;
    public $organisation_account_number;
    public $isOpen;
    public $EditOpen;
    public $the_organisation;


    protected $rules = [
        'organisation_account_number' => 'required|min:5',
        'organisation_name' => 'required',
    ];
    

    protected $listeners = ['deleteOrganisation'];

    public function openRegisterModal(){
        $this->isOpen = true;
    }

    public function closeRegisterModal(){
        $this->isOpen = false;
    }
    public function RegisterOrganisation(){

        $this->validate();

        Organisation::create([
            'organisation_account_number' => $this->organisation_account_number,
            'organisation_name' => $this->organisation_name,
        ]);
        $this->closeRegisterModal();
        $this->emit('swal:alert', [
            'type'    => 'success',
            'title'   => 'Organisation Registered Successfully',
            'timeout' => 10000
        ]);
    }

    public function openModal($id){
        $this->the_organisation = Organisation::find($id);
        $this->organisation_name = $this->the_organisation->organisation_name;
        $this->organisation_account_number = $this->the_organisation->organisation_account_number;
        $this->EditOpen = true;
    }

    public function closeModal(){
        $this->EditOpen = false;
    }

    public function editOrganisation(){
        $this->validate();

        $this->the_organisation->organisation_name =  $this->organisation_name;
        $this->the_organisation->organisation_account_number =  $this->organisation_account_number;

        $this->the_organisation->save();
        
        $this->closeModal();
        $this->organisation_name = '';
        $this->organisation_account_number = '';
        $this->the_organisation = '';
        
        $this->emit('swal:alert', [
            'type'    => 'success',
            'title'   => 'Organisation Details Edited Successfully',
            'timeout' => 10000
        ]);
    }

    private function headerConfig()
    {
        return [
            'organisation_name' => 'Organisation Name',
            'organisation_account_number' => 'Account Number',
            'created_at' => 'Date/Time Registered',
        ];
    }

    public function confirmDelete($id){
        $this->the_organisation = Organisation::find($id);
        $this->emit("swal:confirm", [
            'type'        => 'danger',
            'title'       => 'Confirm Deletion of Organisation',
            'text'        => "Organisation Name: <b>".$this->the_organisation->organisation_account_number."</b><br>Organisation Account Number: <b>".$this->the_organisation->organisation_name."</b></b><br><hr><br><b>Are you sure you want to delete this organisation?</b><br><h5><b>Caution! This action is unreversable</b></h5>",
            'confirmText' => 'Yes, Delete!',
            'method'      => 'deleteOrganisation',
            'params'      => [], // optional, send params to success confirmation
            'callback'    => '', // optional, fire event if no confirmed
        ]);
    }


    public function deleteOrganisation(){
        $this->the_organisation->delete();
        $this->emit('swal:alert', [
            'type'    => 'success',
            'title'   => 'Organisation Deleted Successfully',
            'timeout' => 10000
        ]);
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
