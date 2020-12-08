<div>
    <div class="card">
        <div class="card-header row">
            <h3 class="card-title col-md-10">All Registered Organisations</h3>
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
</div>
