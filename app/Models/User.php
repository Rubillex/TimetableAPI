<?php

namespace App\Models;

use Orchid\Platform\Models\User as Authenticatable;
use Orchid\Screen\AsSource;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $complexity
 * @property float $progress
 * @property float $points
 * @property string $group
 * @property string $fio
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property array|null $permissions
 * @property float $lifes
 * @property float $score
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Orchid\Platform\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @method static \Illuminate\Database\Eloquent\Builder|User averageByDays(string $value, $startDate = null, $stopDate = null, ?string $dateColumn = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User byAccess(string $permitWithoutWildcard)
 * @method static \Illuminate\Database\Eloquent\Builder|User byAnyAccess($permitsWithoutWildcard)
 * @method static \Illuminate\Database\Eloquent\Builder|User countByDays($startDate = null, $stopDate = null, ?string $dateColumn = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User countForGroup(string $groupColumn)
 * @method static \Illuminate\Database\Eloquent\Builder|User defaultSort(string $column, string $direction = 'asc')
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User filters(?\Orchid\Filters\HttpFilter $httpFilter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User filtersApply(iterable $filters = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User filtersApplySelection($selection)
 * @method static \Illuminate\Database\Eloquent\Builder|User maxByDays(string $value, $startDate = null, $stopDate = null, ?string $dateColumn = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User minByDays(string $value, $startDate = null, $stopDate = null, ?string $dateColumn = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User sumByDays(string $value, $startDate = null, $stopDate = null, ?string $dateColumn = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User valuesByDays(string $value, $startDate = null, $stopDate = null, string $dateColumn = 'created_at')
 * @method static \Illuminate\Database\Eloquent\Builder|User whereComplexity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLifes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePermissions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePoints($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereProgress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use AsSource;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'permissions',
        'complexity',
        'progress',
        'points',
        'lifes',
        'fio',
        'group',
        'score'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'permissions',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'permissions'          => 'array',
        'email_verified_at'    => 'datetime',
    ];

    /**
     * The attributes for which you can use filters in url.
     *
     * @var array
     */
    protected $allowedFilters = [
        'id',
        'name',
        'email',
        'lifes',
        'permissions',
    ];

    /**
     * Значения по умолчанию для атрибутов модели.
     *
     * @var array
     */
    protected $attributes = [
        'complexity' => -1,
        'progress'   => -1,
        'points'     => 0,
        'score' => 0,
    ];

    /**
     * The attributes for which can use sort in url.
     *
     * @var array
     */
    protected $allowedSorts = [
        'id',
        'name',
        'email',
        'lifes',
        'score',
        'updated_at',
        'created_at',
    ];
}
