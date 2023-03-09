<?php

namespace App\Models;

use App\Models\BaseModel;
use App\Traits\UuidTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeaveTypeMaster extends BaseModel
{
    use HasFactory, UuidTrait;
    protected $fillable = [
        'name',
        'is_default',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
}
