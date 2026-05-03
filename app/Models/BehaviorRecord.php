<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BehaviorRecord extends Model
{
    protected $fillable = ['student_id', 'user_id', 'violation_type_id', 'vitamin_type_id', 'date', 'notes'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function violationType()
    {
        return $this->belongsTo(ViolationType::class);
    }

    public function vitaminType()
    {
        return $this->belongsTo(VitaminType::class);
    }
}
