<?php

namespace App\Exports;

use App\Enums\BillStatus;
use App\Models\Bill;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BillsExport implements FromCollection, WithHeadings
{
    use Exportable;

    public function headings(): array
    {
        return [
            'Id',
            'Bill Code',
            'Created Date',
            'User Name',
            'Address',
            'Phone',
            'Total',
            'Products'
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $bills = Bill::select('id', 'bill_code', DB::raw('TO_CHAR(created_at, DD/MM/YYYY)'),
            'user_id', 'address', 'phone','total')->where('status', BillStatus::delivered)->get();

        foreach ($bills as $bill){
            $arr = [];
            if (!$bill->user->username){
                $bill->user_id = $bill->user->email;
            }else{
                $bill->user_id = $bill->user->username;
            }
            foreach ($bill->products as $product){
                array_push($arr, $product->name . ' (x' . $product->pivot->quantity . ')');
            }
            $bill->product_name = implode(' | ', $arr);
        }
        return $bills;
    }

}
