<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Test extends Model
{
    use HasFactory;
    use AsSource;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'brefing',
        'question',
        'incorrect_answers',
        'correct_answers',
        'points',
        'needHelp',
        'usersComplited',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'permissions',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'permissions'             => 'array',
        'brefing'                 => 'array',
        'incorrect_answers'       => 'array',
        'correct_answers'         => 'array',
        'needHelp'                => 'bool',
        'usersComplited'          => 'array',
    ];

    /**
     * The attributes for which you can use filters in url.
     *
     * @var array
     */
    protected $allowedFilters = [
        'id',
        'name',
        'question',
        'email',
        'needHelp',
        'permissions',
    ];


    /**
     * The attributes for which can use sort in url.
     *
     * @var array
     */
    protected $allowedSorts = [
        'name',
        'brefing',
        'question',
        'correct_answers',
        'incorrect_answers',
        'points',
        'needHelp',
        'usersComplited',
    ];
}
