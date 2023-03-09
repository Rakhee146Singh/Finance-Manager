<?php

namespace App\Models;

use App\Models\BaseModel;
use App\Traits\UuidTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FinancialYear extends BaseModel
{
    use HasFactory, UuidTrait;
    protected $fillable = [
        'year',
        'start_date',
        'end_date',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
}
