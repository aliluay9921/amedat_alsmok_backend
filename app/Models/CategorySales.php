<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategorySales extends Model
{
    use HasFactory, Uuids;

    protected $guarded = [];
    protected $with = ["employee", "representativ"];
    protected $appends  = ["SequenceInvoicment"];


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
    public function representativ()
    {
        return $this->belongsTo(User::class, 'representative_id')->withTrashed();
    }

    public function invoicements()
    {
        return $this->hasMany(Invoicemnt::class, 'sale_category_id');
    }

    public function getSequenceInvoicmentAttribute()
    {
        return Invoicemnt::where("sale_category_id", $this->id)->count();
    }
}