<?php

namespace App\Services;
use App\Models\Device;
use Illuminate\Support\Str;

class DeviceServiceManager{

    public function __construct()
    {
        header('Content-Type: application/json');
    }

    public function saveDevice(object $request){
        try{
            $device = Device::where(['uid' => $request->uId, 'appId' => $request->appId])->first();
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
            return response()->json(["success"=>false,"message"=>$exception->getMessage()]);
        }

    }
}
