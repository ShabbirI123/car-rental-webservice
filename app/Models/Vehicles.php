<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicles extends Model
{
    use HasFactory;

    protected $table = 'vehicles';

    protected $primaryKey = 'vehicle_id';

    protected $fillable = [
        'vehicle_type_id',
        'location_id',
        'available'
    ];

    public function vehicleType()
    {
        return $this->belongsTo(VehicleTypes::class, 'vehicle_type_id', 'vehicle_type_id');
    }

    public function rentals()
    {
        return $this->hasMany(Rentals::class, 'vehicle_id', 'vehicle_id');
    }
}
