<?php

namespace App\Http\Controllers\API\Admin;

use App\Enums\BillStatus;
use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class StatisticController extends Controller
{
    public function statistic()
    {
        $date = getdate();
        $statistics = Bill::where('status', BillStatus::delivered)
            ->whereMonth('created_at', $date['mon'])
            ->join('bill_details', 'bills.id', '=', 'bill_details.bill_id')
            ->select('bill_details.product_id', DB::raw('sum(bill_details.quantity) as quantities'))
            ->groupBy('bill_details.product_id')
            ->orderBy('quantities', 'Desc')
            ->limit(10)
            ->get();

        foreach ($statistics as $item) {
            $product = Product::select('name')->where('id', $item->product_id)->first();

            $bills = Bill::where('status', BillStatus::delivered)
                ->join('bill_details', 'bills.id', '=', 'bill_details.bill_id')
                ->where('bill_details.product_id', $item->product_id)
                ->where('quantity', '=', function ($query) use ($item) {
                    $query->selectRaw('max(quantity)')->from('bill_details')
                        ->where('bill_details.product_id', $item->product_id);
                })
                ->first();

            $user = User::select('email')->where('id', $bills->user_id)->first();
            $item->product_name = $product->name;
            $item->user = $user->email;

        }
//        dd($statistics);
        return responder()->success($statistics)->respond();
    }

    public function statisticV2()
    {
        $date = getdate();
        $bills = Bill::query()->where('status', BillStatus::delivered)
            ->whereMonth('created_at', $date['mon'])->get();
        $statistic = array();
        foreach ($bills as $bill) {
            foreach ($bill->products as $product) {
                $arr = array();
                $arr['product_id'] = $product->id;
                $arr['product_name'] = $product->name;
                $arr['quantity'] = $product->pivot->quantity;
                if ($bill->user->username){
                    $arr['user'] = $bill->user->username;
                }else{
                    $arr['user'] = $bill->user->email;
                }

                if ($i = array_search($arr['product_id'], array_column($statistic, 'product_id'))) {
                    if ($statistic[$i]['product_id'] == $arr['product_id']) {
                        $statistic[$i]['quantity'] += $arr['quantity'];
                    }
                } else {
                    array_push($statistic, $arr);
                }
            }
        }
        array_multisort(array_map(function ($element) {
            return $element['quantity'];
        }, $statistic), SORT_DESC, $statistic);
        return responder()->success(array_slice($statistic, 0, 10))->respond();
    }
}
