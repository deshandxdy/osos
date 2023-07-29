<?php

namespace App\Models;

use App\Models\Author;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_id',
        'isbn',
        'title',
        'description',
        'price',
        'cover_image',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }
}
