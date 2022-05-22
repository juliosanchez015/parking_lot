<?php


namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class VehiclesResource extends JsonResource
{
    /**
     * @param $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => VehicleResource::collection($this)
        ];
    }
}
