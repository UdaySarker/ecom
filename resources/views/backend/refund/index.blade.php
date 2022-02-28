@extends('backend.layouts.master')

@section('main-content')
    <div class="row mt-3 pl-3">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h4>Refund/Return List</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3 pl-3">
        <div class="col">
            <table class="table table-strip">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Order Number</th>
                        <th scope="col">From</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
@endsection
