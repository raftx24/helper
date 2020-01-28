<?php
/**
 * Created by PhpStorm.
 * User: reza
 * Date: 8/11/18
 * Time: 12:11 PM
 */

namespace Raftx24\Helper\App\Services;

use Cache;
use Eloquent;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\CachedModel
 *
 * @mixin Eloquent
 * @method static Builder|CachedModel newModelQuery()
 * @method static Builder|CachedModel newQuery()
 * @method static Builder|CachedModel query()
 */
class CachedModel extends Model
{
    private static $cached = [];
    protected static $cachedProps = ['id'];

    protected static function boot()
    {
        parent::boot();

        static::updating(function(Model $model) {
            $model->removeCache($model);
        });

        static::deleting(function(Model $model) {
            $model->removeCache($model);
        });
    }

    public static function getCachedProp($id, $prop)
    {
        if (! in_array($prop, static::$cachedProps)) {
            throw new Exception('invalid prop cache');
        }

        if (!isset(self::$cached[static::class])) {
            self::$cached[static::class] = [];
        }

        if (!isset(self::$cached[static::class][$id])) {
            self::$cached[static::class][$id] = self::find($id);
        }

        return self::$cached[static::class][$id]->attributes[$prop];
    }

    public static function storeCache($prop, $value, $obj)
    {
        self::$cached[static::class][$prop][$value] = $obj;
    }

    public static function getCached($prop, $value)
    {
        if(! in_array($prop, static::$cachedProps)) {
            throw new Exception('invalid prop cache');
        }

        self::$cached[static::class] = self::$cached[static::class] ?? [];
        self::$cached[static::class][$prop] = self::$cached[static::class][$prop] ?? [];

        return self::$cached[static::class][$prop][$value] =
            self::$cached[static::class][$prop][$value] ?? self::where($prop, "=", $value)->first();
    }

    public static function getCachedTwoLevel($prop, $value, $timeout = 60)
    {
        if(! in_array($prop, static::$cachedProps)) {
            throw new Exception('invalid prop cache');
        }

        self::$cached[static::class] = self::$cached[static::class] ?? [];
        self::$cached[static::class][$prop] = self::$cached[static::class][$prop] ?? [];

        self::$cached[static::class][$prop][$value] =
            self::$cached[static::class][$prop][$value]
            ?? self::externalCache($prop, $value, $timeout);

        return self::$cached[static::class][$prop][$value];
    }

    public static function getCacheId($id, $timeout=86400)
    {
        return self::getCachedTwoLevel('id', $id, $timeout);
    }

    private static function externalCache($prop, $value, $timeout)
    {
        $externalCacheKey = static::class.'#'.$prop.'#'.$value;

        return Cache::remember($externalCacheKey, $timeout, function () use ($prop, $value) {
            return self::where($prop, "=", $value)->first();
        });
    }

    public static function removeCache(Model $model)
    {
        $keys = collect(static::$cachedProps)
            ->reduce(function ($keys, $prop) use ($model) {
                $value = $model->getOriginal($prop);
                unset(self::$cached[static::class][$prop][$value]);
                $keys[] = static::class.'#'.$prop.'#'.$model->getOriginal($prop);

                return $keys;
            }, []);

        Cache::deleteMultiple($keys);
    }
}
