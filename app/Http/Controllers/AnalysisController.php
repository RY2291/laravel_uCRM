<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class AnalysisController extends Controller
{
  public function index()
  {
    $startedDate = '2022-08-01';
    $endDate = '2022-08-31';

    $dateTest = Order::betweenDate($startedDate, $endDate)
      ->groupBy('id')
      ->selectRaw('id, sum(subtotal) as total, customer_name, status, created_at')
      ->orderBy('created_at')
      ->paginate(50);

    $subQuery = Order::betweenDate($startedDate, $endDate)
      ->where('status', true)
      ->groupBy('id')
      ->selectRaw('id, SUM(subtotal) as total, DATE_FORMAT(created_at, "%Y%m%d") as date');

    $data = DB::table($subQuery)
      ->groupBy('date')
      ->selectRaw('date, sum(total)')
      ->get();

    // dd($data);

    return Inertia::render('Analysis');
  }
}
