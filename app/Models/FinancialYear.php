<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FinancialYear extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $keyType = 'string';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $dates = ['deleted_at'];

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
