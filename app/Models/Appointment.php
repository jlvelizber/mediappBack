<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    protected $fillable = [
        'doctor_id',
        'patient_id',
        'date_time',
        'status',
        'reason',
        'duration_minutes',
    ];


    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'date_time' => 'datetime:Y-m-d H:i'
    ];

    /**
     * Scopes
     */

    /**
     * Scope order by nearby hour
     */
    public function scopeOrderByNearby($query)
    {
        return
            $query->orderByRaw('ABS(TIMESTAMPDIFF(MINUTE, date_time, NOW())) asc')
                ->orderByRaw('DATE_FORMAT(date_time, "%Y-%m-%d") asc')
                ->orderByRaw('DATE_FORMAT(date_time, "%H:%i") asc');
    }


}
