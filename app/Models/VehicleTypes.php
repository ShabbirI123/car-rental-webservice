<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleTypes extends Model
{
    use HasFactory;

    protected $table = 'vehicle_types';

    protected $primaryKey = 'vehicle_type_id';

    protected $fillable = [
        'name',
        'seats',
        'transmission',
        'daily_rate',
        'image'
    ];

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class, 'vehicle_type_id', 'vehicle_type_id');
    }
}
