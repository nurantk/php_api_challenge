<?php

namespace App\Http\Services;

use App\Models\Device;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use PhpParser\Node\Expr\Cast\Object_;
use SebastianBergmann\Type\ObjectType;

class PurchaseServiceManager{

    public function checkApi($operatingSystem,$receiptId):object{
        try{
            if($operatingSystem=='ios'){
                $storeUrl=env('APP_URL')."/api/ios-url";
            }else if($operatingSystem=='google'){
                $storeUrl=env('APP_URL')."/api/google-url";
            }else{
                return response()->json(["success"=>false,"message"=>"Cihaz bulunamadı"]);
            }
            $response = Request::create($storeUrl, 'POST', [
                'receiptId' => $receiptId
            ]);
            $response = app()->handle($response)->getContent();
            $response = json_decode($response);
            return $response;
        }catch (\Exception $exception){
            return response()->json(["success"=>false,"message"=>"Hata"]);
        }
    }
    public function savePurchase(object $request): JsonResponse{
            $purchase = Subscription::where(['clientToken' =>$request->clientToken, 'receiptId' =>$request->receiptId,'status'=>1])->first();
            if(isset($purchase)){
                return response()->json(["success"=>false,"message"=>"Ödeme var","data"=>$purchase]);
            }else{
                $device = Device::select('operatingSystem','id')->where(['clientToken'=>$request->clientToken])->first();
                if(isset($device)){
                    $response=$this->checkApi($device->operatingSystem,$request->receiptId);
                    if($response->success){
                        $subscription=new Subscription();
                        $subscription->uId=$device->id;
                        $subscription->clientToken=$request->clientToken;
                        $subscription->receiptId=$request->receiptId;
                        $subscription->status=$response->status;
                        if(isset($response->expireDate))
                            $subscription->expireDate=$response->expireDate;
                        $subscription->save();
                        if($response->status){
                            return response()->json(["success"=>true,"message"=>"Abonelik tamamlandı","data"=>$subscription]);
                        }
                        return response()->json(["success"=>false,"message"=>"Servis doğrulamadı"]);
                    }
                }
                return response()->json(["success"=>false,"message"=>"Cihaz bulunamadı"]);
            }
    }
    public function checkPurchase(object $request) : JsonResponse{
        try{
            $purchase = Subscription::select('status','expireDate')->where(['clientToken' =>$request->clientToken,'status'=>1])->where('expireDate','>',Carbon::now())->get();
            if($purchase!=null){
                return response()->json(["success"=>true,"data"=>$purchase]);
            }else{
                return response()->json(["success"=>true,"message"=>"Aktif üyelik yok"]);
            }
        }catch (\Exception $exception){
            return response()->json(["success"=>false,"message"=>$exception->getMessage()]);
        }

    }
}
