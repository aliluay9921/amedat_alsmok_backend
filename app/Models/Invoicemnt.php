<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoicemnt extends Model
{
    use HasFactory, Uuids;
    protected $guarded = [];
    protected $with = ['process', 'employee'];


    public function process()
    {
        return $this->belongsTo(CategorySales::class, 'sale_category_id');
    }


    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}