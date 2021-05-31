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
        $bill = Bill::all();
        return responder()->success($bill, BillTransformer::class)->respond();
    }

    public function show(Bill $bill)
    {
        return responder()->success($bill, BillTransformer::class)->respond();
    }

    public function update(StatusRequest $request, Bill $bill)
    {
        $status = BillStatus::getValue($request->input('status'));
        $bill->update(['status' => $status]);
        return responder()->success(['message' => 'Update status success!'])->respond();
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
