<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Cashier\Billable;

class Customer extends Model
{
    use HasFactory;
    use Billable;
    public function orders():HasMany
    {
        return $this->hasMany(Order::class);
    }
}
