<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\AnalysisService;

class AnalysisController extends Controller
{
  public function index(Request $request)
  {
    $subQuery = Order::betweenDate($request->startDate, $request->endDate);

    if($request->type === 'perDay'){
      list($data, $labels, $totals) = AnalysisService::perDay($subQuery);
    }

    return response()->json([
      'data' => $data,
      'labels' => $labels,
      'totals' => $totals,
      'type' => $request->type
    ], Response::HTTP_OK);
  }
}
