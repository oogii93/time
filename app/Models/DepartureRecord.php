<?php

namespace App\Models;

use App\Traits\Auditable;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DepartureRecord extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'departure_records';

    protected $dates = [
        'recorded_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'arrival_id',
        'recorded_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function arrival()
    {
        return $this->belongsTo(ArrivalRecord::class, 'arrival_id');
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
