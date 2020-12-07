<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">All client Users</h3>
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
                    @if(count($data))
                        @foreach($data as $item)
                            <tr>
                                @foreach($headers as $key => $value)
                                    <td>
                                        {{ $item->$key }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    @else
                    @endif
                </tbody>
            </table>
            <div class="row">
                <div class="col-sm-6" style="">
                    {{ $data->links('pagination::bootstrap-4') }}
                </div>
                <div class="clo-sm-6">  </div>
            </div >
        </div>
    </div>
</div>
