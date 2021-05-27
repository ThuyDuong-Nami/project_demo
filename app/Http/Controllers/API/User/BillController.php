<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\CheckOutRequest;
use App\Models\Bill;
use App\Models\Product;
use Illuminate\Http\Request;

class BillController extends Controller
{
    public function createBill(CheckOutRequest $request)
    {
        $validatedData = $request->except('items');
        $user = auth('user')->user();

        $items = $request->input('items');
        $billArr = array_merge([
            'bill_code' => rand(0000000000, 9999999999),
            'user_id' => $user->id,
            'status' => 0
        ],
            $validatedData
        );
        $bill = Bill::create($billArr);

        foreach ($items as $item) {
            $bill->products()->attach($item['product_id'],
                [
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);

            $product = Product::find($item['product_id']);
            $product->update(['quantities' => $product->quantities - $item['quantity']]);
        }
        return responder()->success(['message' => 'Check out success!'])->respond();
    }
}
