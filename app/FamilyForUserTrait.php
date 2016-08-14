<?php  namespace App;

use App\FamilyScope;

trait FamilyForUserTrait {

    /**
     * Boot the Active Events trait for a model.
     *
     * @return void
     */
    public static function bootFamilyForUserTrait()
    {
        static::addGlobalScope(new FamilyScope);
    }

}


?>