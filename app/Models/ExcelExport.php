<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ExcelExport extends Model
{
    protected $table = 'excel_exports';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id', 'entity', 'status', 'file_path', 'error_message', 'params',
    ];

    protected $casts = [
        'params' => 'array',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }
}
