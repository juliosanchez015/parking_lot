<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVehicleRequest;
use App\Http\Requests\UpdateVehicleRequest;
use App\Models\Vehicle;

class VehicleController extends BaseController
{
    protected $model = 'App\Models\Vehicle';
    protected $resource = 'App\Http\Resources\VehicleResource';
    protected $collectionResource = 'App\Http\Resources\VehiclesResource';
}
