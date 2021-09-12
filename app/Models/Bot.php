<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Telegram\Bot\Api;

/**
 * App\Models\Bot
 *
 * @property int $id
 * @property string $name
 * @property string $value
 * @property string|null $description
 * @property int $active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Bot newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bot newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bot query()
 * @method static \Illuminate\Database\Eloquent\Builder|Bot whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bot whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bot whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bot whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bot whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bot whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bot whereValue($value)
 * @mixin \Eloquent
 */
class Bot extends Model
{
    use HasFactory;

    protected $fillable = ['name','value','description'];

    public function getPageAllCompany($skip, $perPage)
    {
        return Company::where('active', true)
            ->where('date_end', '>', now())
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'desc')
            ->skip($skip)->take($perPage)->get();
    }
}
