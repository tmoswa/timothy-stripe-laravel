<?php

namespace App\Models;

use App\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Money\Currency;
use Money\Money;

class OrderItem extends Model
{
    use HasFactory;

    public $casts=[
        'price'=>MoneyCast::class,
        'amount_discount'=>MoneyCast::class,
        'amount_tax'=>MoneyCast::class,
        'amount_subtotal'=>MoneyCast::class,
        'amount_total'=>MoneyCast::class,
    ];
//    protected function price(): Attribute
//    {
//        return Attribute::make(
//            get: function (int $value){
//                return new Money($value,new Currency('USD'));
//            }
//        );
//    }

}
