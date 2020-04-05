<?php


namespace App\Http\Controllers;


use App\Http\Requests\RecordPurchaseRequest;
use App\Http\Resources\PurchaseResource;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Auth\AuthManager;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    /**
     * @var AuthManager
     */
    private $authManager;


    /**
     * PurchaseController constructor.
     */
    public function __construct(AuthManager $authManager)
    {
        $this->authManager = $authManager;
    }

    public function recordPurchase(RecordPurchaseRequest $request)
    {
        $values = $request->only(['price', 'quantity', 'date']);
        $purchase = new Purchase($values);
        $purchase->user()->associate($this->authManager->guard()->user());
        $purchase->save();

        return (new PurchaseResource($purchase))
            ->response()
            ->setStatusCode(201);
    }

    public function getCurrentPurchases()
    {
        /** @var User $user */
        $user = $this->authManager->guard()->user();
        return PurchaseResource::collection($user->currentPurchases);
    }

}
