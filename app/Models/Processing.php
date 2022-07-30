<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Processing extends Model
{
    use HasFactory, Uuids;
    protected $guarded = [];
    protected $with = ['sale_categoreis'];

    public function sale_categoreis()
    {
        return $this->belongsTo(CategorySales::class, 'sale_category_id');
    }
}