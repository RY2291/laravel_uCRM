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

  public function rmfAnalysis($subQuery, $rfmPrms)
  {
    $subQuery = $subQuery->groupBy('id')
      ->selectRaw('
        id
      , customer_id
      , customer_name
      , SUM(subtotal) as totalPerPurchase
      , created_at
      ');

    $subQuery = DB::table($subQuery)->groupBy('customer_id')
      ->selectRaw('
          customer_id
        , customer_name
        , max(created_at) as recentDate
        , datediff(now(), max(created_at)) as recency
        , count(customer_id) as frequency
        , sum(totalPerPurchase) as monetary
      ');

    // 会員毎のRMFランクを計算
    $subQuery = DB::table($subQuery)
      ->selectRaw('
        customer_id
      , customer_name
      , recentDate
      , recency
      , frequency
      , monetary
      , CASE
          WHEN recency < ? THEN 5
          WHEN recency < ? THEN 4
          WHEN recency < ? THEN 3
          WHEN recency < ? THEN 2
          ELSE 1
        END as r
      , CASE
          WHEN frequency >= ? THEN 5
          WHEN frequency >= ? THEN 4
          WHEN frequency >= ? THEN 3
          WHEN frequency >= ? THEN 2
          ELSE 1
        END as f
      , CASE
          WHEN monetary >= ? THEN 5
          WHEN monetary >= ? THEN 4
          WHEN monetary >= ? THEN 3
          WHEN monetary >= ? THEN 2
          ELSE 1
        END as m
      ', $rfmPrms);

    $totals = DB::table($subQuery)->count();

    // ランク毎の数を計算する
    $rCount = DB::table($subQuery)
      ->groupBy('r')
      ->selectRaw('r, COUNT(r)')
      ->orderBy('r', 'desc')
      ->pluck('COUNT(r)');

    $fCount = DB::table($subQuery)
      ->groupBy('f')
      ->selectRaw('f, COUNT(f)')
      ->orderBy('f', 'desc')
      ->pluck('COUNT(f)');

    $mCount = DB::table($subQuery)
      ->groupBy('m')
      ->selectRaw('m, COUNT(m)')
      ->orderBy('m', 'desc')
      ->pluck('COUNT(m)');

    $eachCount = []; // Vue側に渡すようの空の配列
    $rank = 5; // 初期値5

    for($i = 0; $i < 5; $i++){
      array_push($eachCount, [
        'rank' => $rank,
        'r' => $rCount[$i],
        'f' => $fCount[$i],
        'm' => $mCount[$i],
      ]);
      $rank--; // rankを1ずつ減らす
    }

    // RとFで２次元で表示
    $rfData = DB::table($subQuery)
      ->groupBy('r')
      ->selectRaw('
        CONCAT("r_", r) as rRank
      , COUNT(CASE WHEN f = 5 THEN 1 END) as f_5
      , COUNT(CASE WHEN f = 4 THEN 1 END) as f_4
      , COUNT(CASE WHEN f = 3 THEN 1 END) as f_3
      , COUNT(CASE WHEN f = 2 THEN 1 END) as f_2
      , COUNT(CASE WHEN f = 1 THEN 1 END) as f_1
      ')
      ->orderBy('rRank', 'DESC')
      ->get();

    return [$rfData, $totals, $eachCount];
  }
}
