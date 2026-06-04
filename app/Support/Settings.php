<?php

namespace App\Support;

use App\Models\Setting;

class Settings
{
    public static function get(string $key, $default = null)
    {
        return Setting::query()->where('key', $key)->value('value') ?? $default;
    }

    public static function put(string $key, $value): void
    {
        Setting::updateOrCreate(['key' => $key], ['value' => (string) $value]);
    }

    public static function bool(string $key, bool $default = false): bool
    {
        $value = static::get($key);

        return $value === null ? $default : (bool) (int) $value;
    }

    /** Les votes sont-ils masqués au public ? */
    public static function votesHiddenPublic(): bool
    {
        return static::bool('votes_hidden_public');
    }
}
