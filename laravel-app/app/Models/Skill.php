<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Skill extends Model
{
    protected $fillable = [
        'title',
        'description',
    ];

    public function progresses(): HasMany
    {
        return $this->hasMany(SkillProgress::class);
    }
}
