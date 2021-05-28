<?php

namespace App\Models;

use App\Interfaces\SearchableInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Laravel\Scout\Searchable;

/**
 * @property string $name
 * @property string $identifier
 * @property string $meta
 * @property Collection $timetables
 * @property Department department
 * @property Collection schedules
 * @property Collection subscribers
 * @property string route
 * @method static updateOrCreate(array $array, array $array1)
 * @method static where(string $string, string $name)
 * @method static make(array $toArray)
 * @method static firstWhere(string $string, int $course_id)
 * @method static firstOrCreate(array $array, array $array1)
 * @method static count()
 * @method static find(int $int)
 * @method static first()
 */
class Course extends Model implements SearchableInterface
{
    use HasFactory;
    use Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'identifier',
        'year',
        'group',
        'title',
        'department_id',
        'campus_id'
    ];

    /**
     * @return HasMany|Schedule
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function requests()
    {
        return $this->hasMany(Requests::class);
    }

    public function request()
    {
        return $this->hasOne(Requests::class);
    }

    public function lecturers(): Builder|HasMany
    {
        return $this->schedules()->with('lecturers');
    }

    public function searches(): MorphOne
    {
        return $this->morphOne(Search::class, 'searchable');
    }

    public function subscribers(): MorphToMany
    {
        return $this->morphToMany(User::class, 'notifiable');
    }

    public function getRouteKeyName(): string
    {
        return 'identifier';
    }

    public function getRouteTitleAttribute(): string
    {
        return $this->name;
    }

    public function getRouteAttribute(): string
    {
        return route('course', $this);
    }

    public function source(): string
    {
        return sprintf(config('timetable.url.source'), $this->identifier, (string) config('timetable.crawl.week'));
    }

    public function getIconCategoryAttribute(): string
    {
        return $this->department->name;
    }
}
