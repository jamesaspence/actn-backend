<?php


namespace App\Http\Controllers;


use App\Http\Requests\CreatePriceRequest;
use App\Models\Price;
use Illuminate\Support\Facades\Auth;

class PriceController extends Controller
{
    public function createPrice(CreatePriceRequest $request)
    {
        $price = new Price();
        $price->price = $request->price;
        $price->date = $request->date;
        $price->time = $request->time;
        $price->user()->associate(Auth::user());

        $price->save();

        return response([
            'message' => 'success',
            'price' => $price
        ]);
    }
}
