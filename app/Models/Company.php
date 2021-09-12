<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Company
 *
 * @property int $id
 * @property int $user_id
 * @property string $org
 * @property string $title
 * @property string|null $img
 * @property string|null $address
 * @property string|null $working_hours
 * @property string|null $tel
 * @property string|null $site
 * @property string $discount
 * @property string|null $description
 * @property string|null $instagram
 * @property string|null $vk
 * @property string|null $facebook
 * @property string|null $youtube
 * @property int $active
 * @property int|null $priority
 * @property string|null $coordinates
 * @property int|null $views
 * @property string|null $date_end
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Category[] $Categories
 * @property-read int|null $categories_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\City[] $Cities
 * @property-read int|null $cities_count
 * @property-read \App\Models\Partner $Partner
 * @method static \Illuminate\Database\Eloquent\Builder|Company newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Company newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Company query()
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereCoordinates($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereDateEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereFacebook($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereInstagram($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereOrg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereSite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereTel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereViews($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereVk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereWorkingHours($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereYoutube($value)
 * @mixin \Eloquent
 */
class Company extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','org','title','img','address','working_hours','tel','site','discount', 'description','instagram','vk','facebook','youtube','active','priority','coordinates', 'views'];

    protected $with = ['categories:id,name,slug,color', 'cities:id,name,slug'];

    public function Partner()
    {
        return $this->belongsTo(Partner::class, 'user_id', 'id');
    }

    public function Categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function Cities()
    {
        return $this->belongsToMany(City::class);
    }

    public function makeLinkTel()
    {
        return preg_replace_callback('/(\+?\d*)?[\s\-]?((\(\d+\)|\d+)[\s\-]?)?(\d[\s\-]?){7,11}\b/',
            function ($matches) {
                $link = preg_replace("/[^0-9]/", '', $matches[0]);

                if ($link[0] == 7)
                    $link = '+' . $link;

                return '<a href="tel:'.$link.'">'.$matches[0].'</a>';
            },
            $this->tel);
    }
}
