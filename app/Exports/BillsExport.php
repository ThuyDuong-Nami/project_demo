<?php

namespace App\Exports;

use App\Enums\BillStatus;
use App\Models\Bill;
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
            'Total'
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $bills = Bill::select('id', 'bill_code', DB::raw('DATE_FORMAT(created_at, "%d/%m/%Y")'),
            'user_id', 'address', 'phone','total')->where('status', BillStatus::delivered)->get();
        foreach ($bills as $bill){
            $user = User::select('username')->where('id', $bill->user_id)->first();
            $bill->user_id = $user->username;
        }
        return $bills;
    }

}
