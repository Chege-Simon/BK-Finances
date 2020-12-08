<div x-data="{ open: false }">
    <div class="container jumbotron pt-3">
        <div class="row">
            <p class="col-12">Add account to deposit to or withdraw from</p>
            <div class="col-3">
                <button class="btn btn-success" wire:click="openModal" data-toggle="modal" data-target="#addAccountModel">Add Account</button>
            </div>
            <div class="col-7">
            </div>
            <div class="col-2">
                <div wire:loading class="spinner-grow text-dark text-success ml-auto" role="status">
                    <span class="sr-only">Just a moment...</span>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-lg-1"></div>
            <div class="col-lg-4 col-sm-12 col-md-4">
                <div class="card" style="width: 18rem;">
                    <img src="/deposit.png" style="height: 200px" class="card-img-top p-5 " alt="deposit">
                    <div class="card-body">
                        <h5 class="card-title font-weight-bolder">Want to Deposit</h5>
                        </br>
                        <div>
                            <p>Amount in KSh</p>
                            <input wire:model="deposit_amount" type="number" min="1000">
                            </br>
                        </div>
                        <div>
                            @if($expected_deposit_amount > 0)
                                <p>You account will be funded with</p>
                                <p>USD {{ $expected_deposit_amount }}</p>
                                <hr>
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-primary m-1 mt-3" data-toggle="modal" data-target="#depositModel">
                                    Deposit Now
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2">
            </div>
            <div class="col-lg-4 col-sm-12 col-md-4">
                <div class="card" style="width: 18rem;">
                    <img src="/withdraw.png" style="height: 200px" class="card-img-top p-5" alt="withdraw">
                    <div class="card-body">
                        <h5 class="card-title font-weight-bolder">Want to Withdraw</h5>
                        </br>
                        <div>
                            <p>Amount in USD</p>
                            <input wire:model="withdraw_amount" type="number" min="10">
                            </br>
                        </div>
                        <div>
                            @if($expected_withdraw_amount > 0)
                            <p>You will receive</p>
                            <p>Ksh {{ $expected_withdraw_amount }}</p>
                            <hr>
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-success m-1 mt-3" data-toggle="modal" data-target="#withdrawModel">
                                Withdraw Now
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Deposit Modal -->
    <div class="modal fade" id="depositModel" tabindex="-1" role="dialog" aria-labelledby="depositModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="depositModelLabel">Deposit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <select class="custom-select">
                        <option selected>Select account to deposit to:</option>
                        <option value="1">Binary CRT2310498234</option>
                        <option value="2">TemplerFx 982237</option>
                        <option value="3">Exness 32DSJA421</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary"  data-dismiss="modal">Deposit</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Withdraw Modal -->
    <div class="modal fade" id="withdrawModel" tabindex="-1" role="dialog" aria-labelledby="depositModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="withdrawModalLabel">Deposit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>from companies website go to withdraw page.</p>
                    <p>Select payment via agent,</p>
                    <p>For payment agent select BKFinances,</p>
                    <p>payment agent ID (LEAVE BLANK)</p>
                    <p>Enter amount in USD</p>
                    <hr>
                    <p>Funds will be sent to</p>
                    <p>MPESA Number: 0712345678</p>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" data-dismiss="modal">Okay, Done</button>
                </div>
            </div>
        </div>
    </div>
    <!-- New Account Modal -->
    @if($isOpen)
    <div class="modal d-block" id="addAccountModel" tabindex="-1" role="dialog" aria-labelledby="addAccountModelLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAccountModelLabel">Add Account</h5>
                    <button type="button" wire:click="closeModal" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Register your account here:</p>
                    <form  wire:submit.prevent="addAccount">
                        <div class="form-group">
                            <label for="organisation">Broker</label>
                            <select class="custom-select" class="@error('organisation') is-invalid @enderror" wire:model="organisation" id="organisation" required>
                                <option selected>Select Broker:</option>
                                @foreach($organisations as $organisation)
                                    <option value="{{ $organisation->id }}">{{ $organisation->organisation_name }}</option>
                                @endforeach
                            </select>
                            @error('organisation') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="account_number">Account Number</label>
                            <input type="text" class="form-control" class="@error('account_number') is-invalid @enderror" wire:model="account_number" id="account_number" required>
                            @error('account_number') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Add Account</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
