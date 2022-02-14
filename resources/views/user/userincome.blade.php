@extends('user.layouts.master')

@section('main-content')
    <div class="row">
        <div class="col">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Debit Amount</th>
                        <th scope="col">Credit Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $d)
                        <tr>
                            <td>{{$d->dt_amt}}</td>
                            <td>{{$d->ct_amt}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
