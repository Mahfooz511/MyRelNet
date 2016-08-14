<?php  namespace App;

use App\FamilyacessScope;

trait FamilyacessTrait {

    /**
     * Boot the Active Events trait for a model.
     *
     * @return void
     */
    public static function bootFamilyacessTrait()
    {
        static::addGlobalScope(new FamilyacessScope);
    }

}


?>