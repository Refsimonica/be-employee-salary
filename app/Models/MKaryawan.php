<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use App\Blameable;

class MKaryawan extends Model
{
    use HasFactory, SoftDeletes, Blameable;

    protected $table = 'm_karyawan';
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
                $model->deleted_by = auth()->id();
                $model->save();
            });
		}
	}

    public function departemen() {
		return $this->belongsTo('App\Models\MDepartemen', 'm_departemen_id', 'id');
	}

    public function bagian() {
		return $this->belongsTo('App\Models\MBagian', 'm_bagian_id', 'id');
	}

    public function jabatan() {
		return $this->belongsTo('App\Models\MJabatan', 'm_jabatan_id', 'id');
	}

    public function penempatan() {
		return $this->belongsTo('App\Models\MPenempatan', 'm_penempatan_id', 'id');
	}
}
