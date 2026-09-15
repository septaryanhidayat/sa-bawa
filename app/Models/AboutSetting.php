<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutSetting extends Model
{
    protected $guarded = [];

    public static function getAllKeyValues(): array
    {
        return self::pluck('value', 'key')->toArray();
    }

    public static function setKeyValue(string $key, ?string $value): void
    {
        self::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}
