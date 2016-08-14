<?php  namespace App;

use App\UserpreferrenceScope;

trait UserpreferrenceTrait {

    /**
     * Boot the Active Events trait for a model.
     *
     * @return void
     */
    public static function bootUserpreferrenceTrait()
    {
        static::addGlobalScope(new UserpreferrenceScope);
    }

}


?>