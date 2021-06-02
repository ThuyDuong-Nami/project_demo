<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;
use Illuminate\Support\Facades\Lang;

final class BillStatus extends Enum implements LocalizedEnum
{
    const confirm = 0;
    const pickup = 1;
    const delivering = 2;
    const delivered = 3;
    const canceling = 4;
    const canceled = 5;
}
