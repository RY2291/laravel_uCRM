<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\AnalysisService;
use App\Services\AnalysisServiceInterface;

class AnalysisController extends Controller
{
  protected $analysisService;

  public function __construct(AnalysisServiceInterface $analysisService)
  {
    $this->analysisService = $analysisService;
  }

  public function index(Request $request)
  {
    $subQuery = Order::betweenDate($request->startDate, $request->endDate);

    if($request->type === 'perDay'){
      list($data, $labels, $totals) = $this->analysisService->perDay($subQuery);
    }
    if($request->type === 'perMonth') {
      list($data, $labels, $totals) = $this->analysisService->perMonth($subQuery);
    }
    if($request->type === 'perYear') {
      list($data, $labels, $totals) = $this->analysisService->perYear($subQuery);
    }

    return response()->json([
      'data' => $data,
      'labels' => $labels,
      'totals' => $totals,
      'type' => $request->type
    ], Response::HTTP_OK);
  }
}
