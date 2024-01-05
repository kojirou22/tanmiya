<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Owner extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'designation', 
    ];
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }
}
