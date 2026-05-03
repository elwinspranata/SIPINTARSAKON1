<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VitaminType extends Model
{
    protected $fillable = ['category', 'name', 'points'];

    public function behaviorRecords()
    {
        return $this->hasMany(BehaviorRecord::class);
    }
}
