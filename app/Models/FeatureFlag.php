<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class FeatureFlag extends Model
{
    protected $fillable = ['key', 'description'];

    public function planos(): BelongsToMany
    {
        return $this->belongsToMany(Plano::class, 'plan_features', 'feature_flag_id', 'plano_id')
            ->withPivot('enabled');
    }
}
