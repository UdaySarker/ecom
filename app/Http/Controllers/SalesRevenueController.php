<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class SalesRevenueController extends Controller
{
    public function index()
    {
        $orders=Order::all();
        return view('backend.sales.index')
        ->with('orders',$orders);
    }

    public function pdf()
    {
        $orders=Order::all();
        // return $order;
        $file_name='totalsales.pdf';
        // return $file_name;
        $pdf = app('dompdf.wrapper');
        // $pdf=new Dompdf();
        $pdf=$pdf->loadview('backend.order.pdf',compact('orders'));
        return $pdf->download($file_name);
    }
}
