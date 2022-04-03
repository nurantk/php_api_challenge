<?php

namespace App\Http\Services;
use App\Models\Device;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;

class DeviceServiceManager{
    public function saveDevice(object $request): JsonResponse{
        try{
            $device = Device::where(['uId' => $request->uId, 'appId' => $request->appId])->first();
            if(isset($device)){
                return response()->json(["success"=>true,"register"=>"OK","data"=>$device]);
            }else{
                $device=new Device();
                $device->uId=$request->uId;
                $device->appId=$request->appId;
                $device->language=$request->language;
                $device->operatingSystem=$request->operatingSystem;
                $device->clientToken=Str::random(35);
                $device->save();
                return response()->json(["success"=>true,"register"=>"OK","data"=>$device]);
            }
        }catch (\Exception $exception){
            Log::error("Device record error:".$exception->getMessage());
            return response()->json(["success"=>false,"message"=>$exception->getMessage()]);
        }

    }
}
