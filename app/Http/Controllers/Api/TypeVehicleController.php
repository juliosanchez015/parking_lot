<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTypeVehicleRequest;
use App\Http\Requests\UpdateTypeVehicleRequest;
use App\Models\TypeVehicle;

class TypeVehicleController extends BaseController
{
    protected $model = 'App\Models\TypeVehicle';
    protected $resource = 'App\Http\Resources\TypeVehicleResource';
    protected $collectionResource = 'App\Http\Resources\TypeVehiclesResource';
}
