<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeVehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'fee',
    ];

    public static function relationship() : array
    {
        return [];
    }

    public static function createRules(): array
    {
        return [
            'name' => 'required|max:255',
            'fee' => 'required',
        ];
    }

    public static function updateRules(): array
    {
        return [
            'name' => 'required|max:255',
            'fee' => 'required',
        ];
    }
}
