<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    protected $table = 'classes';
    protected $fillable = [
        'name',
        'tingkat',
        'is_active',
    ];

    public function students()
    {
        return $this->hasMany(Student::class, 'class_id');
    }
}
