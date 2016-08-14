<?php namespace App;
 
use Illuminate\Database\Query\Builder as BaseBuilder;
 
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ScopeInterface;
 
class RelationScope implements ScopeInterface {
 
    /**
     * Apply scope on the query.
     * 
     * @param \Illuminate\Database\Eloquent\Builder  $builder
     * @param \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $builder->where('user_id', '=', \Auth::id());
    }
 
    /**
     * Remove scope from the query.
     * 
     * @param \Illuminate\Database\Eloquent\Builder  $builder
     * @param \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function remove(Builder $builder,Model $model)
    {
        $query = $builder->getQuery();
 
        foreach ((array) $query->wheres as $key => $where)
        {
            if($where['column'] == 'user_id') {

                    unset($query->wheres[$key]);

                    $query->wheres = array_values($query->wheres);

                }
        }
    }
    
}