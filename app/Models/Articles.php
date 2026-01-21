<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class Articles extends Model
{
    /** @use HasFactory<\Database\Factories\ArticlesFactory> */
    use HasFactory;
    protected $fillable = [
        'titre',
        'contenu',
        'image',
        'en_vedette',
        'a_la_une',
        'auteur_id',
        'categorie_id',
    ];

    protected static function booted()
{
    static::creating(function ($model) {
        $model->slug = now()->format('Ymd') . '-' .Str::slug($model->nom ?? $model->titre);
    });
}
}


