<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceRecord extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'attendance_records';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'registered_at',
    ];

    /**
     * Get the user associated with the attendance record.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    protected $casts = [
        'registered_at' => 'datetime',
    ];
}
