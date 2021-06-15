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
            ->join('bill_details', 'bills.id', '=', 'bill_details.bill_id')
            ->whereMonth('bills.created_at', $date['mon'])
            ->select('bill_details.product_id', DB::raw('sum(bill_details.quantity) as quantities'))
            ->groupBy('bill_details.product_id')
            ->orderBy('quantities', 'Desc')
            ->limit(10)->distinct()
            ->get();

        foreach ($statistics as $item) {
            $product = Product::select('name')->where('id', $item->product_id)->first();

            $bills = Bill::where('status', BillStatus::delivered)
                ->join('bill_details', 'bills.id', '=', 'bill_details.bill_id')
                ->where('bill_details.product_id', $item->product_id)
                ->where('quantity', '=', function ($query) use ($item){
                    $query->selectRaw('max(quantity)')->from('bill_details')
                        ->where('bill_details.product_id', $item->product_id);
                })
                ->first();

            $user = User::where('id', $bills->user_id)->first();
            $item->product_name = $product->name;
            if ($user->username){
                $item->user = $user->username;
            }else{
                $item->user = $user->email;
            }
        }
//        dd($statistics);
        return responder()->success($statistics)->respond();
    }
}
