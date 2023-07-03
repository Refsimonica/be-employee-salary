<?php

namespace App;

use App\Observers\ActionObserver;

trait Blameable {
    public static function bootBlameable() {
        static::observe(ActionObserver::class);
    }
}
