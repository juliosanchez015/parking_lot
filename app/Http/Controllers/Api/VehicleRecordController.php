<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVehicleRecordRequest;
use App\Http\Requests\UpdateVehicleRecordRequest;
use App\Models\Vehicle;
use App\Models\VehicleRecord;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VehicleRecordController extends BaseController
{
    protected $model = 'App\Models\VehicleRecord';
    protected $resource = 'App\Http\Resources\VehicleRecordResource';
    protected $collectionResource = 'App\Http\Resources\VehicleRecordsResource';


    public function store(Request $request)
    {
        $class = $this->model;

        $validator = Validator::make($request->all(), $class::createRules());

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $object = new $class([
            "vehicle_entrance_at"=> Carbon::now(),
            "vehicle_id"=> $request->vehicle_id,
        ]);
        $object->save();
        return new $this->resource($object);
    }

    public function update(Request $request, $id)
    {
        $class = $this->model;

        $rules = $class::updateRules();
        foreach ($rules as $key => $value) {
            if(is_string($value)){
                if (strpos($value, 'unique') !== false) {
                    $rules[ $key ] = $value . ',' . $key . ',' . $id;
                }
            }
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $item = $class::find($id);

        if (!$item) {
            return response()->json([
                'message' => __('entities.IDNotFound'),
                'error'   => __('entities.IDNotFound'),
            ], 404);
        }

        $startTime = Carbon::parse($item->vehicle_entrance_at);
        $finishTime = Carbon::now();
        $fee = $item->vehicle->type->fee;
        $diff =  $startTime->diffInMinutes($finishTime);
        $item->update([
            "vehicle_exit_at"=> Carbon::now(),
            "minutes"=> $diff,
            "cost"=> $diff*$fee,
        ]);

        return new $this->resource($item);
    }

    public function report(Request $request){
        $class = $this->model;
        $cost = $class::where("vehicle_id",$request->vehicle_id)
            ->whereBetween('vehicle_entrance_at', [$request->start, $request->end])
            ->whereNotNull("vehicle_exit_at")
            ->sum("cost");

        $vehicle = Vehicle::with("type")->find($request->vehicle_id);
        return response()->json(['cost' => $cost,'date_start' => $request->start,'date_end' => $request->end,"vehicle"=>$vehicle]);
    }
}
