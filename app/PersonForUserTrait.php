<?php  namespace App;

use App\PersonScope;

trait PersonForUserTrait {

    /**
     * Boot the Active Events trait for a model.
     *
     * @return void
     */
    public static function bootPersonForUserTrait()
    {
        static::addGlobalScope(new PersonScope);
    }

}


?>