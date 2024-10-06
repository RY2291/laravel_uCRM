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
    $startedDate = '2021-08-01';
    $endDate = '2024-09-31';

    // $dateTest = Order::betweenDate($startedDate, $endDate)
    //   ->groupBy('id')
    //   ->selectRaw('id, sum(subtotal) as total, customer_name, status, created_at')
    //   ->orderBy('created_at')
    //   ->paginate(50);

    // $subQuery = Order::betweenDate($startedDate, $endDate)
    //   ->where('status', true)
    //   ->groupBy('id')
    //   ->selectRaw('id, SUM(subtotal) as total, DATE_FORMAT(created_at, "%Y%m%d") as date');

    // $data = DB::table($subQuery)
    //   ->groupBy('date')
    //   ->selectRaw('date, sum(total)')
    //   ->get();

    // // dd($data);

    // // 購入ID毎にグルーピング
    // $subQuery = Order::betweenDate($startedDate, $endDate)
    //   ->groupBy('id')
    //   ->selectRaw('id, customer_id, customer_name, SUM(subtotal) as totalPerPurchase');

    // // 会員毎にまとめて購入金額順にソート
    // $subQuery = DB::table($subQuery)
    //   ->groupBy('customer_id')
    //   ->selectRaw('customer_id, customer_name, SUM(totalPerPurchase) as total')
    //   ->orderBy('total', 'desc');

    // // 購入金額順に連番を振る
    // DB::statement('set @row_num = 0;');
    // $subQuery = DB::table($subQuery)
    //   ->selectRaw('
    //     @row_num := @row_num + 1 as row_num,
    //     customer_id,
    //     customer_name,
    //     total
    //   ');

    // // 全体の件数をカウントし、1/10の値や合計金額を取得
    // $count = DB::table($subQuery)->count();
    // $total = DB::table($subQuery)->selectRaw('sum(total) as total')->get();
    // $totalAmount = $total[0]->total;

    // $decile = ceil($count / 10);

    // $bindValues = [];
    // $tempValue = 0;
    // for ($i=0; $i < 10 ; $i++) { 
    //   array_push($bindValues, 1 + $tempValue);
    //   $tempValue += $decile;
    //   array_push($bindValues, 1 + $tempValue);
    // }
    // // dd($bindValues);
    // DB::statement('set @row_num = 0;');
    // $subQuery = DB::table($subQuery)
    //   ->selectRaw('
    //       row_num
    //     , customer_id
    //     , customer_name
    //     , total
    //     , case
    //         WHEN ? <= row_num and row_num < ? then 1
    //         WHEN ? <= row_num and row_num < ? then 2
    //         WHEN ? <= row_num and row_num < ? then 3
    //         WHEN ? <= row_num and row_num < ? then 4
    //         WHEN ? <= row_num and row_num < ? then 5
    //         WHEN ? <= row_num and row_num < ? then 6
    //         WHEN ? <= row_num and row_num < ? then 7
    //         WHEN ? <= row_num and row_num < ? then 8
    //         WHEN ? <= row_num and row_num < ? then 9
    //         WHEN ? <= row_num and row_num < ? then 10
    //       end as decile
    //   ', $bindValues);
    
    //   // グループ毎の合計、平均
    // $subQuery = DB::table($subQuery)
    //   ->groupBy('decile')
    //   ->selectRaw('
    //       decile
    //     , FORMAT(round(avg(total)), 0) as avg
    //     , sum(total) as totalPerGroup
    //   ');

    // DB::statement("set @total = {$totalAmount} ;");
    // $data = DB::table($subQuery)
    //   ->selectRaw('
    //     decile
    //   , avg
    //   , totalPerGroup
    //   , round(100 * totalPerGroup / @total, 1) as totalRatio
    //   ')
    //   ->get();

    // $subQuery = Order::betweenDate($startedDate, $endDate)->groupBy('id')
    //   ->selectRaw('
    //     id
    //   , customer_id
    //   , customer_name
    //   , SUM(subtotal) as totalPerPurchase
    //   , created_at
    //   ');

    // $subQuery = DB::table($subQuery)->groupBy('customer_id')
    //   ->selectRaw('
    //       customer_id
    //     , customer_name
    //     , max(created_at) as recentDate
    //     , datediff(now(), max(created_at)) as recency
    //     , count(customer_id) as frequency
    //     , sum(totalPerPurchase) as monetary
    //   ');

    // // 会員毎のRMFランクを計算
    // $rfmPrms = [14, 28, 60, 90, 7, 5, 3, 2, 300000, 200000, 100000, 30000 ];
    // $subQuery = DB::table($subQuery)
    //   ->selectRaw('
    //     customer_id
    //   , customer_name
    //   , recentDate
    //   , recency
    //   , frequency
    //   , monetary
    //   , CASE
    //       WHEN recency < ? THEN 5
    //       WHEN recency < ? THEN 4
    //       WHEN recency < ? THEN 3
    //       WHEN recency < ? THEN 2
    //       ELSE 1
    //     END as r
    //   , CASE
    //       WHEN frequency >= ? THEN 5
    //       WHEN frequency >= ? THEN 4
    //       WHEN frequency >= ? THEN 3
    //       WHEN frequency >= ? THEN 2
    //       ELSE 1
    //     END as f
    //   , CASE
    //       WHEN monetary >= ? THEN 5
    //       WHEN monetary >= ? THEN 4
    //       WHEN monetary >= ? THEN 3
    //       WHEN monetary >= ? THEN 2
    //       ELSE 1
    //     END as m
    //   ', $rfmPrms);
    // // ランク毎の数を計算する
    // $rCount = DB::table($subQuery)
    //   ->groupBy('r')
    //   ->selectRaw('r, count(r)')
    //   ->orderBy('r', 'desc')
    //   ->pluck('COUNT(r)');

    // $fCount = DB::table($subQuery)
    //   ->groupBy('f')
    //   ->selectRaw('f, count(f)')
    //   ->orderBy('f', 'desc')
    //   ->pluck('COUNT(f)');

    // $mCount = DB::table($subQuery)
    //   ->groupBy('m')
    //   ->selectRaw('m, count(m)')
    //   ->orderBy('m', 'desc')
    //   ->pluck('COUNT(m)');

    // $eachCount = []; // Vue側に渡すようの空の配列
    // $rank = 5; // 初期値5
    
    // for($i = 0; $i < 5; $i++)
    // {
    //   array_push($eachCount, [
    //       'rank' => $rank, 
    //       'r' => $rCount[$i],  
    //       'f' => $fCount[$i],  
    //       'm' => $mCount[$i], 
    //     ]);  
    //     $rank--; // rankを1ずつ減らす 
    // }

    // // RとFで２次元で表示
    // $rfData = DB::table($subQuery)
    //   ->groupBy('r')
    //   ->selectRaw('
    //     CONCAT("r_", r) as rRank
    //   , COUNT(CASE WHEN f = 5 THEN 1 END) as f_5
    //   , COUNT(CASE WHEN f = 4 THEN 1 END) as f_4
    //   , COUNT(CASE WHEN f = 3 THEN 1 END) as f_3
    //   , COUNT(CASE WHEN f = 2 THEN 1 END) as f_2
    //   , COUNT(CASE WHEN f = 1 THEN 1 END) as f_1
    //   ')
    //   ->orderBy('rRank', 'DESC')
    //   ->get();

    // dd($rfData);

    return Inertia::render('Analysis');
  }
}
