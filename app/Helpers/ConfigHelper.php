<?php

namespace App\Helpers;

use App\Models\Config;

class ConfigHelper {

    protected $configModel;

    public function __construct()
    {
            $this->configModel = new Config;
    }

    public function get($keyval = "%")
    {
        $keyval = trim($keyval);
        $settings = $this->configModel->where('key', "like", $keyval)->get();

        if ($keyval == "%") {
            $vals = $settings->map(function($item) {
                return $item->value;
            })->toArray();

            $keys = $settings->map(function($item) {
                return $item->key;
            })->toArray();

            return array_combine($keys, $vals);
        }
        return $settings->first()->value;
    }

    public function set($keyval, $value)
    {
        $matches = $this->configModel->where('key', $keyval)->get();

        if ($matches->count()) {
            $setting = $matches->first();

        } else {
            $setting = new Config();
        }

        $setting->key = $keyval;
        $setting->value = $value;

        $setting->save();

        return $setting->value;
    }

}
