<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rentals extends Model
{
    use HasFactory;

    protected $table = 'rentals';

    protected $primaryKey = 'rental_id';

    protected $fillable = [
        'customer_id',
        'vehicle_id',
        'start_date',
        'end_date',
        'total_days',
        'invoice_id'
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id', 'vehicle_id');
    }
}
