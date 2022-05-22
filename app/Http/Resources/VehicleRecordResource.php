<?php


namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;


class VehicleRecordResource extends JsonResource
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
            'vehicle_entrance_at' => $this->vehicle_entrance_at,
            'vehicle_exit_at' => $this->vehicle_exit_at,
            'minutes' => $this->minutes,
            'cost' => $this->cost,
            'vehicle_id' => $this->vehicle_id,
            'vehicle' => $this->vehicle,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
