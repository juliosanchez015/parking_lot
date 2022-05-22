<?php


namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class TypeVehiclesResource extends JsonResource
{
    /**
     * @param $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => TypeVehicleResource::collection($this)
        ];
    }
}
