<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\Subtotal;
use Illuminate\Support\Carbon;

class Order extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::addGlobalScope(new Subtotal);
    }

    public function scopeBetweenDate(object $query, string $startDate = null, string $endDate = null)
    {
        $endDateAdd1 = Carbon::parse($endDate)->addDays(1);
        if(is_null($startDate) && is_null($endDate)){
            return $query;
        }

        if(!is_null($startDate) && is_null($endDate)){
            return $query->where('created_at', '>=', $startDate);
        }

        if(is_null($startDate) && !is_null($endDate)){
            return $query->where('created_at', '<=', $endDateAdd1);
        }

        if(!is_null($startDate) && !is_null($endDate)){
            return $query->whereBetween('created_at', [$startDate, $endDateAdd1]);
        }
    }
}