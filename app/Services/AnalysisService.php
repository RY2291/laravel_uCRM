<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class AnalysisService
{

  public static function perDay($subQuery)
  {
    $subQuery->where('status', true)
      ->groupBy('id')
      ->selectRaw('id, SUM(subtotal) as total, DATE_FORMAT(created_at, "%Y%m%d") as date');

    $data = DB::table($subQuery)
      ->groupBy('date')
      ->selectRaw('date, sum(total) as total')
      ->get();

    $labels = $data->pluck('date');
    $totals = $data->pluck('total');

    return [$data, $labels, $totals];
  }
}
