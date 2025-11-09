<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Skill extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
    ];

    public function progresses(): HasMany
    {
        return $this->hasMany(SkillProgress::class);
    }
}
