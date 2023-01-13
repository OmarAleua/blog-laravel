<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    /* hace lo contrario a la funcion $fillable del modelo User.php
    no envia estos datos de forma masiva para que no haya problemas de inyeccion de SQL
    fillable: asigna y actualiza los datos de forma masiva - los envia a la db
    guarded: no queremos que se asigne masivamente */
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    // Relacion 1 a muchos inversa (comment-user)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relacion 1 a muchos inversa (comments-article)
    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
