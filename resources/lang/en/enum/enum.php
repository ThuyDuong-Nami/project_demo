<?php
use App\Enums\BillStatus;

return [

    BillStatus::class => [
        BillStatus::confirm => 'Wait For Confirmation',
        BillStatus::pickup => 'Wait For The Goods',
        BillStatus::delivering => 'Delivering',
        BillStatus::delivered => 'Delivered',
        BillStatus::canceling => 'Canceling',
        BillStatus::canceled => 'Canceled',
    ],

];
