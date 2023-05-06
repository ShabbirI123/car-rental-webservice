<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class Customers extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'customers';

    protected $primaryKey = 'customer_id';

    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'password',
    ];
}
