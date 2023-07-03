<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MShift extends Model
{
    use HasFactory;

    protected $table = 'm_shift';
	protected $guarded = [];

    protected static $logAttributes = ['*'];
	protected static $ignoreChangedAttributes = ['created_at', 'updated_at'];
	protected static $logOnlyDirty = true;
	protected static $submitEmptyLogs = false;

}
