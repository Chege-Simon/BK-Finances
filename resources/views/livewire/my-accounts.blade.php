<div>
    <div>
        <div class="jumbotron">
            <table class="table table-striped table-bordered">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">Account Number</th>
                    <th scope="col">Organisation</th>
                    <th scope="col">Date/Time Registered</th>
                </tr>
                </thead>
                <tbody>
                @foreach($accounts as $account)
                    <tr>
                        <td>{{ $account->user_account_number }}</td>
                        <td>{{ App\Models\Organisation::find($account->organisation_id)->organisation_name }}</td>
                        <td>{{ $account->created_at }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
