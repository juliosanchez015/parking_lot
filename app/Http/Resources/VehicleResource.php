<?php


namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;


class VehicleResource extends JsonResource
{
    protected $populate;

    public function __construct($resource, $populate = false)
    {
        parent::__construct($resource);
        $this->resource = $resource;
        $this->populate = $populate;
    }

    /**
     * @param $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'license_plate' => $this->license_plate,
            'type_vehicle_id' => $this->type_vehicle_id,
            'type_vehicle' => new TypeVehicleResource($this->type),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
