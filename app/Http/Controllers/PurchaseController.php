<?php


namespace App\Http\Controllers;


use App\Http\Requests\RecordPurchaseRequest;
use App\Http\Resources\PurchaseResource;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{

    public function recordPurchase(RecordPurchaseRequest $request)
    {
        $values = $request->only(['price', 'quantity', 'date']);
        $purchase = new Purchase($values);
        $purchase->user()->associate(Auth::user());
        $purchase->save();

        return (new PurchaseResource($purchase))
            ->response()
            ->setStatusCode(201);
    }

}
