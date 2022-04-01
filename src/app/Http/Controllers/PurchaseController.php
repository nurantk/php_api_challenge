<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubscriptionCheckRequest;
use App\Http\Requests\SubscriptionRequest;
use App\Http\Services\PurchaseServiceManager;
use Illuminate\Http\JsonResponse;

class PurchaseController extends Controller
{
    public $purchaseManager;

    public function __construct(PurchaseServiceManager $purchaseManager)
    {
        $this->purchaseManager = $purchaseManager;
    }
    public function save(SubscriptionRequest $request): JsonResponse{
        return $this->purchaseManager->savePurchase($request);
    }
    public function check(SubscriptionCheckRequest $request): JsonResponse{
        return $this->purchaseManager->checkPurchase($request);
    }
}
