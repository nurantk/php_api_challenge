<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'uId' => new DeviceResource($this->uId),
            'receiptId' => $this->receiptId,
            'clientToken' => $this->clientToken,
            'expireDate'=>$this->expireDate,
            'status' => $this->status
        ];
    }
}
