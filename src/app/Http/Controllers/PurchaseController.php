<?php

namespace App\Http\Controllers;

use App\Services\PurchaseServiceManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Validator;

class PurchaseController extends Controller
{

    public $purchaseManager;

    public function __construct(PurchaseServiceManager $purchaseManager)
    {
        $this->purchaseManager = $purchaseManager;
    }
    public function save(Request $request): JsonResponse{
        $validateData = Validator::make($request->all(), [
            'receiptId' => 'required',
            'clientToken' => 'required',
        ]);

        if ($validateData->fails()) {
            return response()->json(["success"=>false,"message"=>"Eksik veri"]);
        }

        return $this->purchaseManager->savePurchase($request);
    }
    public function check(Request $request): JsonResponse{
        $validateData = Validator::make($request->all(), [
            'clientToken' => 'required',
        ]);
        if ($validateData->fails()) {
            return response()->json(["success"=>false,"message"=>"Eksik veri"]);
        }
        return $this->purchaseManager->checkPurchase($request->clientToken);
    }
}
