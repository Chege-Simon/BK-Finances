<div>
    <div class="card">
        <div class="card-header row">
            <h3 class="card-title col-md-3">All Registered Organisations</h3>
            <button class="col-md-2 btn btn-success"wire:click="openRegisterModal">Register Organisation</button>
            <span class="col-md-5"></span>
            <input wire:model="searchTerm" type="text" class="form-control rounded col-md-2" placeholder="Search...">

        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <table class="table table-striped">
                <thead class="thead-dark" >
                @foreach($headers as $key => $value)
                    <th style="cursor: pointer" wire:click="sort('{{ $key }}')">
                        @if($sortColumn == $key)
                            <span>{!! $sortDirection == 'asc' ? '&#8659;':'&#8657;' !!}</span>
                        @endif
                        {{ $value }}
                    </th>
                @endforeach
                    <th class="text-center">Action</th>
                </thead>
                <tbody>
                @if(count($organisations))
                    @foreach($organisations as $organisation)
                        <tr>
                            @foreach($headers as $key => $value)
                                <td>
                                    {{ $organisation->$key }}
                                </td>
                            @endforeach
                            <td class="text-center">
                                <a href="#" wire:click="openModal({{ $organisation->id }})" >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" class="bi bi-pencil-square text-primary mr-4" viewBox="0 0 16 16">
                                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                    </svg>
                                </a>
                                |
                                <a href="#" wire:click="confirmDelete({{ $organisation->id }})" >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" class="bi bi-trash text-danger ml-4" viewBox="0 0 16 16">
                                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                        <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="{{ count($headers) }}"><h2>No Results found!</h2></td>
                    </tr>
                @endif
                </tbody>
            </table>
            <div class="row">
                <div class="col-sm-6" style="">
                    {{ $organisations->links('pagination::bootstrap-4') }}
                </div>
                <div class="clo-sm-6">  </div>
            </div >
        </div>
    </div>
    <!-- Add Organisation Modal -->
    @if($isOpen)
    <div class="modal d-block" id="addOrganisationModel" tabindex="-1" role="dialog" aria-labelledby="addOrganisationModelLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addOrganisationModelLabel">Register Organisation</h5>
                    <button type="button" wire:click="closeRegisterModal" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Register New Organisation here:</p>
                    <form  wire:submit.prevent="RegisterOrganisation">
                        
                        <div class="form-group">
                            <label for="organisation_name">Organisation Name</label>
                            <input type="text" class="form-control" class="@error('organisation_name') is-invalid @enderror" wire:model="organisation_name" id="organisation_name"  required>
                            @error('organisation_name') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="organisation_account_number">Organisation Account Number</label>
                            <input type="text" class="form-control" class="@error('organisation_account_number') is-invalid @enderror" wire:model="organisation_account_number" id="organisation_account_number"  required>
                            @error('organisation_account_number') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="modal-footer">
                        <button type="button" wire:click="closeRegisterModal" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">Cancel</button>
                            <button type="submit" class="btn btn-success">Register</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
    <!-- Edit Organisation Modal -->
    @if($EditOpen)
    <div class="modal d-block" id="editOrganisationModel" tabindex="-1" role="dialog" aria-labelledby="editOrganisationModelLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editOrganisationModelLabel">Edit Organisation</h5>
                    <button type="button" wire:click="closeModal" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Edit Organisation here:</p>
                    <form  wire:submit.prevent="editOrganisation">
                        
                        <div class="form-group">
                            <label for="organisation_name">Organisation Name</label>
                            <input type="text" class="form-control" class="@error('organisation_name') is-invalid @enderror" wire:model="organisation_name" id="organisation_name"  required>
                            @error('organisation_name') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="organisation_account_number">Organisation Account Number</label>
                            <input type="text" class="form-control" class="@error('organisation_account_number') is-invalid @enderror" wire:model="organisation_account_number" id="organisation_account_number"  required>
                            @error('organisation_account_number') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="modal-footer">
                        <button type="button" wire:click="closeModal" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">Cancel</button>
                            <button type="submit" class="btn btn-success">Edit Organisation</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
