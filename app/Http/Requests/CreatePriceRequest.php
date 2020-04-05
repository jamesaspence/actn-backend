<?php

namespace App\Http\Requests;

use App\Models\Price;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property string time
 * @property string date
 * @property integer price
 */
class CreatePriceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return !is_null($this->user());
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
            'date' => [
                'required',
                'date',
                Rule::unique('prices')->where('user_id', $this->user()->id)
                    ->where('time', $this->time)
            ],
            'time' => [
                'required',
                Rule::in([Price::TIME_MORNING, Price::TIME_EVENING]),
                Rule::unique('prices')->where('user_id', $this->user()->id)
                    ->where('date', $this->date)
            ]
        ];
    }
}
