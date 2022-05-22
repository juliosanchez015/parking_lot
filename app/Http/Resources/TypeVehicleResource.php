<?php


namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;


class TypeVehicleResource extends JsonResource
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
            'name' => $this->name,
            'fee' => $this->fee,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
