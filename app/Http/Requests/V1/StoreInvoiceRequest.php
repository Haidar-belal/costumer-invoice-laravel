<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->user();
        return $user != null && $user->tokenCan('create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            '*.customerId' => ['required', 'integer'],
            '*.amount' => ['required', 'numeric'],
            '*.status' => ['required' , Rule::in(['P', 'B', 'V', 'p', 'b', 'v'])],
            '*.billedDate' => ['required', 'date_format:Y-m-d H:i:s'],
            '*.paidDate' => ['date_format:Y-m-d H:i:s']
        ];
    }
    protected function prepareForValidation()
    {
        $data = [];

        foreach ($this->toArray() as $item) {
            $item['customer_id'] = $item['customerId'] ?? null;
            $item['billed_date'] = $item['billedDate'] ?? null;
            $item['paid_date'] = $item['paidDate'] ?? null;

            $data[] = $item;
        }
        $this->merge($data);
    }
}
