<?php

namespace App\Http\Requests;

use App\Models\Price;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property string time
 * @property Carbon date
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
        //TODO validate user has not already created a price for this time period
        //TODO ensure carbon timestamp is respected
        return [
            'price' => 'required|integer|min:1',
            'date' => 'required|date',
            'time' => ['required', Rule::in([Price::TIME_MORNING, Price::TIME_EVENING])]
        ];
    }
}
