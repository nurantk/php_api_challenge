<?php

namespace App\Http\Controllers;

use App\Http\Services\DeviceServiceManager;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Http\JsonResponse;

class DeviceController extends Controller
{

    public $deviceManager;

    public function __construct(DeviceServiceManager $deviceManager)
    {
        $this->deviceManager = $deviceManager;
    }

    public function register(Request $request): JsonResponse{
        $validateData = Validator::make($request->all(), [
            'uId' => 'required',
            'appId' => 'required',
            'language' => 'required',
            'operatingSystem' => 'required',
        ]);

        if ($validateData->fails()) {
            return response()->json(["success"=>false,"message"=>"Cihaz bilgileri eksik."]);
        }
        return $this->deviceManager->saveDevice($request);
    }
}
