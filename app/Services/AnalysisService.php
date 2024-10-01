<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class AnalysisService implements AnalysisServiceInterface
{

  public function perDay($subQuery)
  {
    $subQuery->where('status', true)
      ->groupBy('id')
      ->selectRaw('id, SUM(subtotal) as total, DATE_FORMAT(created_at, "%Y%m%d") as date');

    $data = $this->fetchData($subQuery);
    $labels = $data->pluck('date');
    $totals = $data->pluck('total');

    return [$data, $labels, $totals];
  }

  public function perMonth($subQuery)
  {
    $subQuery->where('status', true)
      ->groupBy('id')
      ->selectRaw('id, SUM(subtotal) as total, DATE_FORMAT(created_at, "%Y%m") as date');

    $data = $this->fetchData($subQuery);
    $labels = $data->pluck('date');
    $totals = $data->pluck('total');

    return [$data, $labels, $totals];
  }

  public function perYear($subQuery)
  {
    $subQuery->where('status', true)
      ->groupBy('id')
      ->selectRaw('id, SUM(subtotal) as total, DATE_FORMAT(created_at, "%Y") as date');

    $data = $this->fetchData($subQuery);
    $labels = $data->pluck('date');
    $totals = $data->pluck('total');

    return [$data, $labels, $totals];
  }

  private function fetchData($subQuery){
    return DB::table($subQuery)
      ->groupBy('date')
      ->selectRaw('date, sum(total) as total')
      ->get();
  }
}
