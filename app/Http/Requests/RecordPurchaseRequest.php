<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string date
 */
class RecordPurchaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'price' => 'required|integer|min:1',
            'quantity' => [
                'required',
                'integer',
                'min:10',
                function ($attribute, $value, $fail) {
                    if ($value % 10 !== 0) {
                        $fail('Must buy in batches of 10.');
                    }
                }
            ],
            'date' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    $date = Carbon::createFromFormat('Y-m-d', $value);
                    if ($date->dayOfWeek !== 0) {
                        $fail('Can only purchase on Sundays.');
                    }
                }
            ]
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'date' => is_null($this->date) ? Carbon::now()->toDateString() : $this->date
        ]);
    }
}
