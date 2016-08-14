<?php  namespace App;

use App\RelationScope;

trait RelationForUserTrait {

    /**
     * Boot the Active Events trait for a model.
     *
     * @return void
     */
    public static function bootRelationForUserTrait()
    {
        static::addGlobalScope(new RelationScope);
    }

}


?>