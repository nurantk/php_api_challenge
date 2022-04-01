<?php

namespace App\Http\Controllers;

use App\Http\Services\DeviceServiceManager;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\DeviceRequest;

class DeviceController extends Controller
{

    public $deviceManager;

    public function __construct(DeviceServiceManager $deviceManager)
    {
        $this->deviceManager = $deviceManager;
    }

    public function register(DeviceRequest  $request): JsonResponse{
        return $this->deviceManager->saveDevice($request);
    }
}
