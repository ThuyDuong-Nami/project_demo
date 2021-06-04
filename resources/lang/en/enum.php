<?php
use App\Enums\BillStatus;

return [
//    BillStatus::confirm => 'Wait For Confirmation',
//    BillStatus::pickup => 'Wait For The Goods',
//    BillStatus::delivering => 'Delivering',
//    BillStatus::delivered => 'Delivered',
//    BillStatus::canceling => 'Canceling',
//    BillStatus::canceled => 'Canceled',
    BillStatus::confirm => 'Chờ Xác Nhận',
    BillStatus::pickup => 'Chờ Lấy Hàng',
    BillStatus::delivering => 'Đang Giao',
    BillStatus::delivered => 'Đã Giao Thành Công',
//    BillStatus::canceling => 'Đang Hủy',
    BillStatus::canceled => 'Đã Hủy',
];
