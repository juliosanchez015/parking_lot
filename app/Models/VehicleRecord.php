<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'vehicle_entrance_at',
        'vehicle_exit_at',
        'minutes',
        'cost',
    ];

    public static function relationship() : array
    {
        return ["vehicle","vehicle.type"];
    }

    public static function createRules(): array
    {
        return [
            'vehicle_id' => 'required|max:255|exists:vehicles,id',
        ];
    }

    public static function updateRules(): array
    {
        return [
            'vehicle_id' => 'required|max:255|exists:vehicles,id',
        ];
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
