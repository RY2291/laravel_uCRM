<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\AnalysisServiceInterface;
use App\Services\DecileServiceInterface;

class AnalysisController extends Controller
{
  protected $analysisService;
  protected $decileService;

  public function __construct(
    AnalysisServiceInterface $analysisService,
    DecileServiceInterface $decileService
    )
  {
    $this->analysisService = $analysisService;
    $this->decileService = $decileService;
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
    if($request->type === 'decile') {
      list($data, $labels, $totals) = $this->decileService->decile($subQuery);
    }
    if($request->type === 'rfm') {
      list($rfData, $totals, $eachCount) = $this->analysisService->rmfAnalysis($subQuery, $request->rfmPrms);

      return response()->json([
        'data' => $rfData,
        'type' => $request->type,
        'eachCount' => $eachCount,
        'totals' => $totals,
      ], Response::HTTP_OK);
    }

    return response()->json([
      'data' => $data,
      'labels' => $labels,
      'totals' => $totals,
      'type' => $request->type
    ], Response::HTTP_OK);
  }
}
