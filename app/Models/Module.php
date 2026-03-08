<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Module extends Model
{
    protected $fillable = ['name', 'slug', 'description'];

    public function planos(): BelongsToMany
    {
        return $this->belongsToMany(Plano::class, 'plan_modules', 'module_id', 'plano_id');
    }
}
