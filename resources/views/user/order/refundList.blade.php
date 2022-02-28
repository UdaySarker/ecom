@extends('user.layouts.master')

@section('main-content')
    <div class="row mt-3 pl-3">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h4>Return/Refund List</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Type</th>
                        <th>Payment Method</th>
                        <th>Refund Amount</th>
                        <th>Admin Status</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
