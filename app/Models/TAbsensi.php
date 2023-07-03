<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use App\Blameable;

class TAbsensi extends Model
{
    use HasFactory, SoftDeletes, Blameable;

    protected $table = 't_absensi';
	protected $dates = ['deleted_at'];
	protected $guarded = [];
	protected $hidden = ['created_at','created_by','updated_at', 'updated_by', 'deleted_at','deleted_by'];

    protected static $logAttributes = ['*'];
	protected static $ignoreChangedAttributes = ['created_at','created_by','updated_at', 'updated_by', 'deleted_at','deleted_by'];
	protected static $logOnlyDirty = true;
	protected static $submitEmptyLogs = false;

    protected static function boot() {
		parent::boot();
		if (Auth::check()) {
            static::deleting(function ($model) {
                $model->absensi_karyawan()->each(function($absensi_karyawan) {
					$absensi_karyawan->forceDelete();
				});
                $model->deleted_by = auth()->id();
                $model->save();
            });
		}
	}

    public function absensi_karyawan () {
		return $this->hasMany('App\Models\TAbsensiKaryawan', 't_absensi_id');
    }
}
