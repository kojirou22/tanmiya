<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;
    protected $fillable = [

        'project_no','batch','owner_id','address','type_id','status','cost','image'
    ];
    public function owner(): BelongsTo
    {
        return $this->belongsTo(Owner::class);
    }
    
    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }

}
