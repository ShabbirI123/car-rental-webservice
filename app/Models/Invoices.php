<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customers;

class Invoices extends Model
{
    use HasFactory;

    protected $primaryKey = 'invoice_id';

    protected $fillable = [
        'customer_id',
        'total_amount',
        'original_currency',
        'total_amount_selected_currency',
        'selected_currency',
        'invoice_date',
        'payment_status',
    ];

    protected $dates = [
        'invoice_date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customers::class, 'customer_id', 'customer_id');
    }
}
