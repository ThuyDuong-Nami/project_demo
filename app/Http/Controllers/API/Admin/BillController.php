<?php

namespace App\Http\Controllers\API\Admin;

use App\Enums\BillStatus;
use App\Exports\BillsExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StatusRequest;
use App\Models\Bill;
use App\Transformers\Admin\BillTransformer;

class BillController extends Controller
{
    public function index()
    {
        $perPage = request()->input('perPage');
        $bill = Bill::paginate($perPage);
        return responder()->success($bill, BillTransformer::class)->respond();
    }

    public function show(Bill $bill)
    {
        return responder()->success($bill, BillTransformer::class)->respond();
    }

    public function update(StatusRequest $request, Bill $bill)
    {
        if ($bill->status == BillStatus::canceled){
            return responder()->success(['message' => 'This bill cannot edited!'])->respond();
        }else{
            $status = BillStatus::getValue($request->input('status'));
            $bill->update(['status' => $status]);
            if ($status == BillStatus::canceled) {
                foreach ($bill->products as $product) {
                    $product->update(['quantities' => $product->quantities + $product->pivot->quantity]);
                }
            }
            return responder()->success(['message' => 'Update status success!'])->respond();
        }
    }

    public function search()
    {
        $word = request()->input('word');
        $search = Bill::where('bill_code', 'like', '%'.$word.'%');
        return responder()->success($search, BillTransformer::class)->respond();
    }

    public function export()
    {
        $file = request()->input('fileName');
        return (new BillsExport())->download($file, \Maatwebsite\Excel\Excel::CSV);
    }
}
