<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Screen\AsSource;

class PicturesToTests extends Model
{
    use HasFactory;
    use AsSource;
    use Attachable;
    /**
     * @var string[]
     */
    protected $fillable = [
        'path_to_picture'
    ];

    protected $allowedFilters = [
        'path_to_picture'
    ];

    protected $allowedSorts = [
        'path_to_picture'
    ];
}
