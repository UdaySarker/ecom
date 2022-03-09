<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width">
  <title>JS Bin</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>
<body>
  <div class="container">
    <div class="row">
      <div class="col-md-4 offset-md-4">
        <p>BoiBazar</p>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-6 offset-sm-3">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Order Number</th>
                    <th scope="col">Order Date</th>
                    <th scope="col">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td>{{$order->id}}</td>
                        <td>{{$order->order_number}}</td>
                        <td>{{$order->created_at->format('d/m/y')}}</td>
                        <td>{{$order->sub_total}}</td>
                    </tr>
               @endforeach
               <tr>
                   <td colspan="3" class="text-right">Total</td>
                   <td>{{Helper::totalOrderAmount()}}</td>
               </tr>
            </tbody>
        </table>
      </div>
    </div>
  </div>
<script src="https://code.jquery.com/jquery.min.js"></script>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

</body>
</html>
