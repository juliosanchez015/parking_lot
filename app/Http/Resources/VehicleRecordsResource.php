<?php


namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class VehicleRecordsResource extends JsonResource
{
    /**
     * @param $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => VehicleRecordResource::collection($this)
        ];
    }
}
