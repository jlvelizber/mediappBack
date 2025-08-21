<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Patient extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'lastname',
        'email',
        'phone',
        'address',
        'dob',
        'document',
        'gender',
        'doctor_id'
    ];

    protected $casts = [
        'dob' => 'date:Y-m-d',
    ];

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->name} {$this->lastname}";
    }


    /**
     * Get age of the patient.
     */
    public function getAgeAttribute(): int
    {
        return abs(now()->diffInYears($this->dob));
    }

    /**
     * Relationships
     */

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
