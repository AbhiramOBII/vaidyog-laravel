<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    protected $fillable = ['group', 'key', 'value'];

    /**
     * Get a setting value by key.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $cacheKey = "site_setting.{$key}";

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $setting = static::where('key', $key)->first();

        if ($setting !== null && $setting->value !== null) {
            Cache::forever($cacheKey, $setting->value);
            return $setting->value;
        }

        return $default;
    }

    /**
     * Set a setting value by key.
     */
    public static function set(string $key, mixed $value, string $group = 'general'): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group]
        );

        Cache::forget("site_setting.{$key}");
    }

    /**
     * Get all settings for a group.
     */
    public static function getGroup(string $group): array
    {
        return static::where('group', $group)
            ->pluck('value', 'key')
            ->toArray();
    }

    /**
     * Flush all cached settings.
     */
    public static function flushCache(): void
    {
        $keys = static::pluck('key');
        foreach ($keys as $key) {
            Cache::forget("site_setting.{$key}");
        }
    }
}
