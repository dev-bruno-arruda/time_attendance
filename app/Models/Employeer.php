<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employeer extends Model
{
    protected $fillable = ['name', 'email', 'role', 'birth_date', 'cpf', 'cep', 'address', 'number', 'state', 'city', 'manager_id']; 

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
