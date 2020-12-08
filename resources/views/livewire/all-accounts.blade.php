<div>
    <div class="card">
        <div class="card-header row">
            <h3 class="card-title col-md-10">All Registered Accounts</h3>
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
                        {{ is_array($value) ? $value['label'] : $value }}
                    </th>
                @endforeach
                </thead>
                <tbody>
                @if(count($accounts))
                    @foreach($accounts as $account)
                        <tr wire:key="{{ $account->id }}">
                            @foreach($headers as $key => $value)
                                <td wire:key="{{ $key }}">
                                    {!! is_array($value) ? $value['func']($account->$key) :$account->$key!!}
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
                    {{ $accounts->links('pagination::bootstrap-4') }}
                </div>
                <div class="clo-sm-6">  </div>
            </div >
        </div>
    </div>
</div>
