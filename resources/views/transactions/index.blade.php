@extends('layouts.app')

@section('page')
    <div class="card radius-10">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div>
                    <h5 class="mb-0">All Transactions</h5>
                </div>
                <div class="font-22 ms-auto">
                    Account Type <strong>{{ Auth()->user()->account_type }}</strong>
                </div>
                <div class="font-22 ms-auto">
                    Balance <strong>{{ number_format(Auth()->user()->balance, 2) }}</strong>
                </div>
            </div>
            <hr>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Fee</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transactions as $transaction)
                            <tr>
                                <td>{{ $transaction->id }}</td>
                                <td>{{ $transaction->user->name }}</td>
                                <td>{{ ucfirst($transaction->transaction_type) }}</td>
                                <td>TK {{ number_format($transaction->amount, 2) }}</td>
                                <td>TK {{ number_format($transaction->fee, 2) }}</td>
                                <td>{{ $transaction->created_at->format('Y-m-d') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
