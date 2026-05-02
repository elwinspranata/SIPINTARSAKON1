<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViolationType extends Model
{
    protected $fillable = ['category', 'sub_category', 'name', 'points'];

    public function behaviorRecords()
    {
        return $this->hasMany(BehaviorRecord::class);
    }
}
