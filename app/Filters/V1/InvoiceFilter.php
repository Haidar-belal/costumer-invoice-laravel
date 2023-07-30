<?php

namespace App\Filters\V1;

use App\Filters\ApiFilter;
use Illuminate\Http\Request;

class InvoiceFilter extends ApiFilter {
    protected $safeParam = [
        'customer_id' => ['eq'],
        'amount' => ['eq', 'gt', 'lt', 'gte', 'lte'],
        'status' => ['eq', 'ne'],
        'billed_date' => ['eq', 'gt', 'lt'],
        'paid_date' => ['eq', 'gt', 'lt'],
    ];

    protected $operationMap = [
        'eq' => '=',
        'lt' => '<',
        'gt' => '>',
        'gte' => '>=',
        'lte' => '<=',
        'ne' => '!='
    ];
}
