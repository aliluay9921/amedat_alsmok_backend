<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategorySales extends Model
{
    use HasFactory, Uuids;

    protected $guarded = [];
    protected $with = ["employee"];

    // public function processing()
    // {
    //     return $this->belongsTo(Processing::class, 'sale_category_id');
    // }
    /**
     * Get the user that owns the CategorySales
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}