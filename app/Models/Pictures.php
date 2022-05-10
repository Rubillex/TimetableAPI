<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Pictures extends Model
{
    use HasFactory;
    use AsSource;

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'test_id',
        'path_to_picture'
    ];

    protected $allowedFilters = [
        'user_id',
        'test_id',
        'path_to_picture'
    ];

    protected $allowedSorts = [
        'user_id',
        'test_id',
        'path_to_picture'
    ];
}
