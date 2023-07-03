<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Blameable;

class Setting extends Model
{
    use HasFactory, Blameable;

    protected $primaryKey = 'name';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'settings';
	protected $guarded = [];
	protected $hidden = ['created_at','created_by'];

    protected static $logAttributes = ['*'];
	protected static $ignoreChangedAttributes = ['created_at','created_by','updated_at', 'updated_by'];
	protected static $logOnlyDirty = true;
	protected static $submitEmptyLogs = false;

    protected static function boot() {
		parent::boot();
		// if (Auth::check()) {
        //     static::deleting(function ($model) {
        //         $model->deleted_by = auth()->id();
        //         $model->save();
        //     });
		// }
	}
}
