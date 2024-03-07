<?php

namespace App\Models;

use App\Traits\Auditable;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArrivalRecord extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'arrival_records';

    protected $dates = [
        'recorded_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_id',
        'recorded_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    protected $casts = [
        'recorded_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function arrivalDepartureRecords()
    {
        return $this->hasMany(DepartureRecord::class, 'arrival_id', 'id');
    }
    public function DepartureRecord()
    {
        return $this->hasOne(DepartureRecord::class, 'arrival_id', 'id');
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getRecordedAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setRecordedAtAttribute($value)
    {

        $this->attributes['recorded_at'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }
}