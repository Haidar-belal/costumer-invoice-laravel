<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateInvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->user();
        return $user != null && $user->tokenCan('update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $method = $this->method();
        if ($method == 'PUT') {
            return [
                'amount' => ['required', 'numeric'],
                'status' => ['required', Rule::in(['P', 'B', 'V', 'p', 'b', 'v'])],
                'billedDate' => ['required', 'date_format:Y-m-d H:i:s'],
                'paidDate' => ['date_format:Y-m-d H:i:s'],
            ];
        } else {
            return [
                'amount' => ['sometimes', 'required', 'numeric'],
                'status' => ['sometimes', 'required', Rule::in(['P', 'B', 'V', 'p', 'b', 'v'])],
                'billedDate' => ['sometimes', 'required', 'date_format:Y-m-d H:i:s'],
                'paidDate' => ['sometimes', 'date_format:Y-m-d H:i:s'],
            ];
        }
    }
    protected function prepareForValidation()
    {
        if ($this->postalCode) {
            $this->merge([
                'billed_date' => $this->postalCode,
                'paid_date' => $this->postalCode,
            ]);
        }
    }
}
