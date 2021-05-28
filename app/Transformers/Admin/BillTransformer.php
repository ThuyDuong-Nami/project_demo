<?php

namespace App\Transformers\Admin;

use App\Enums\BillStatus;
use App\Models\Bill;
use Flugg\Responder\Transformers\Transformer;

class BillTransformer extends Transformer
{
    /**
     * List of available relations.
     *
     * @var string[]
     */
    protected $relations = [];

    /**
     * List of autoloaded default relations.
     *
     * @var array
     */
    protected $load = [
        'products' => ProductTransformer::class
    ];

    /**
     * Transform the model.
     *
     * @param  \App\Models\Bill $bill
     * @return array
     */
    public function transform(Bill $bill)
    {
        return [
            'id' => $bill->id,
            'code' => $bill->bill_code,
            'created_at' => date('d/m/Y', strtotime($bill->created_at)),
            'total' => $bill->total,
            'status' => __('enum.'.$bill->status)
        ];
    }
}
