<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'license_plate',
        'type_vehicle_id',
    ];

    public static function relationship() : array
    {
        return ["type"];
    }

    public static function createRules(): array
    {
        return [
            'license_plate' => 'required|max:255|unique:vehicles',
            'type_vehicle_id' => 'required|exists:type_vehicles,id',
        ];
    }

    public static function updateRules(): array
    {
        return [
            'license_plate' => 'required|max:255',
            'type_vehicle_id' => 'required',
        ];
    }

    /**
     * Get the phone associated with the user.
     */
    public function type()
    {
        return $this->belongsTo(TypeVehicle::class,"type_vehicle_id");
    }

    public function record()
    {
        return $this->hasMany(VehicleRecord::class);
    }
}
