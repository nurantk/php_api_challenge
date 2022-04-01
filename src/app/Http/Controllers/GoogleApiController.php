<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Http\JsonResponse;

class GoogleApiController extends Controller
{
    public function checkPurchase(Request $request): JsonResponse{

        $validateData = Validator::make($request->all(), [
            'receiptId' => 'required',
        ]);
        if ($validateData->fails()) {
            return response()->json(["success"=>false,"message"=>"Eksik data"]);
        }
        $receiptCode=$request->receiptId[strlen($request->receiptId)-1];
        if($receiptCode%2==1){
            return response()->json(["success"=>true,"status"=>true,"expireDate"=>Carbon::now()->addDays(7)->utcOffset(-360)->format('Y-m-d H:i:s')]);
        }else{
            return response()->json(["success"=>true,"status"=>false]);
        }

    }
}
