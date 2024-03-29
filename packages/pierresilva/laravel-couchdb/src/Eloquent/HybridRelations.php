<?php

namespace pierresilva\CouchDB\Eloquent;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Str;
use pierresilva\CouchDB\Relations\BelongsTo;
use pierresilva\CouchDB\Relations\BelongsToMany;
use pierresilva\CouchDB\Relations\HasMany;
use pierresilva\CouchDB\Relations\HasOne;
use pierresilva\CouchDB\Relations\MorphTo;
use pierresilva\CouchDB\Relations\MorphToMany;

trait HybridRelations
{
    /**
     * Define a one-to-one relationship.
     *
     * @param string $related
     * @param string $foreignKey
     * @param string $localKey
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function hasOne($related, $foreignKey = null, $localKey = null)
    {
        // Check if it is a relation with an original model.
        if (!is_subclass_of($related, \Robsonvn\CouchDB\Eloquent\Model::class)) {
            return parent::hasOne($related, $foreignKey, $localKey);
        }

        $foreignKey = $foreignKey ?: $this->getForeignKey();

        $instance = new $related();

        $localKey = $localKey ?: $this->getKeyName();

        return new HasOne($instance->newQuery(), $this, $foreignKey, $localKey);
    }

    /**
     * Define a polymorphic one-to-one relationship.
     *
     * @param string $related
     * @param string $name
     * @param string $type
     * @param string $id
     * @param string $localKey
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function morphOne($related, $name, $type = null, $id = null, $localKey = null)
    {
        // Check if it is a relation with an original model.
        if (!is_subclass_of($related, \Robsonvn\CouchDB\Eloquent\Model::class)) {
            return parent::morphOne($related, $name, $type, $id, $localKey);
        }

        $instance = new $related();

        list($type, $id) = $this->getMorphs($name, $type, $id);

        $localKey = $localKey ?: $this->getKeyName();

        return new MorphOne($instance->newQuery(), $this, $type, $id, $localKey);
    }

    public function morphToMany($related, $name, $table = NULL, $foreignPivotKey = NULL, $relatedPivotKey = NULL, $parentKey = NULL, $relatedKey = NULL,
                                $inverse = false)
    {
      // Check if it is a relation with an original model.
      if (!is_subclass_of($related, \pierresilva\CouchDB\Eloquent\Model::class)) {
          return parent::morphToMany($related, $name, $table, $foreignPivotKey, $relatedPivotKey, $parentKey, $relatedKey,
              $inverse);
      }

      $caller = $this->guessBelongsToManyRelation();

      // First, we will need to determine the foreign key and "other key" for the
      // relationship. Once we have determined the keys we will make the query
      // instances, as well as the relationship instances we need for these.
      $instance = $this->newRelatedInstance($related);

      $foreignKey = $foreignPivotKey ?: $name.'_id';

      $relatedKey = $relatedPivotKey ?: $instance->getForeignKey();

      // Now we're ready to create a new query builder for this related model and
      // the relationship instances for this relation. This relations will set
      // appropriate query constraints then entirely manages the hydrations.
      $table = $table ?: Str::plural($name);

      return new MorphToMany(
          $instance->newQuery(), $this, $name, $table,
          $foreignKey, $relatedKey, $caller, $inverse
      );
    }

    /**
     * Define a one-to-many relationship.
     *
     * @param string $related
     * @param string $foreignKey
     * @param string $localKey
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function hasMany($related, $foreignKey = null, $localKey = null)
    {
        // Check if it is a relation with an original model.
        if (!is_subclass_of($related, \pierresilva\CouchDB\Eloquent\Model::class)) {
            return parent::hasMany($related, $foreignKey, $localKey);
        }

        $foreignKey = $foreignKey ?: $this->getForeignKey();

        $instance = new $related();

        $localKey = $localKey ?: $this->getKeyName();

        return new HasMany($instance->newQuery(), $this, $foreignKey, $localKey);
    }

    /**
     * Define a polymorphic one-to-many relationship.
     *
     * @param string $related
     * @param string $name
     * @param string $type
     * @param string $id
     * @param string $localKey
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function morphMany($related, $name, $type = null, $id = null, $localKey = null)
    {
        // Check if it is a relation with an original model.
        if (!is_subclass_of($related, \pierresilva\CouchDB\Eloquent\Model::class)) {
            return parent::morphMany($related, $name, $type, $id, $localKey);
        }

        $instance = new $related();

        // Here we will gather up the morph type and ID for the relationship so that we
        // can properly query the intermediate table of a relation. Finally, we will
        // get the table and create the relationship instances for the developers.
        list($type, $id) = $this->getMorphs($name, $type, $id);

        $table = $instance->getTable();

        $localKey = $localKey ?: $this->getKeyName();

        return new MorphMany($instance->newQuery(), $this, $type, $id, $localKey);
    }

    /**
     * Define an inverse one-to-one or many relationship.
     *
     * @param string $related
     * @param string $foreignKey
     * @param string $otherKey
     * @param string $relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function belongsTo($related, $foreignKey = null, $otherKey = null, $relation = null)
    {
        // If no relation name was given, we will use this debug backtrace to extract
        // the calling method's name and use that as the relationship name as most
        // of the time this will be what we desire to use for the relationships.
        if (is_null($relation)) {
            list($current, $caller) = debug_backtrace(false, 2);

            $relation = $caller['function'];
        }

        // Check if it is a relation with an original model.
        if (!is_subclass_of($related, \pierresilva\CouchDB\Eloquent\Model::class)) {
            return parent::belongsTo($related, $foreignKey, $otherKey, $relation);
        }

        // If no foreign key was supplied, we can use a backtrace to guess the proper
        // foreign key name by using the name of the relationship function, which
        // when combined with an "_id" should conventionally match the columns.
        if (is_null($foreignKey)) {
            $foreignKey = Str::snake($relation).'_id';
        }

        $instance = new $related();

        // Once we have the foreign key names, we'll just create a new Eloquent query
        // for the related models and returns the relationship instance which will
        // actually be responsible for retrieving and hydrating every relations.
        $query = $instance->newQuery();

        $otherKey = $otherKey ?: $instance->getKeyName();

        return new BelongsTo($query, $this, $foreignKey, $otherKey, $relation);
    }

    /**
     * Define a polymorphic, inverse one-to-one or many relationship.
     *
     * @param string $name
     * @param string $type
     * @param string $id
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function morphTo($name = NULL, $type = NULL, $id = NULL, $ownerKey = NULL)
    {
        // If no name is provided, we will use the backtrace to get the function name
        // since that is most likely the name of the polymorphic interface. We can
        // use that to get both the class and foreign key that will be utilized.
        if (is_null($name)) {
            list($current, $caller) = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);

            $name = Str::snake($caller['function']);
        }

        list($type, $id) = $this->getMorphs($name, $type, $id);

        // If the type value is null it is probably safe to assume we're eager loading
        // the relationship. When that is the case we will pass in a dummy query as
        // there are multiple types in the morph and we can't use single queries.
        if (is_null($class = $this->$type)) {
            return new MorphTo(
                $this->newQuery(), $this, $id, null, $type, $name
            );
        }

        // If we are not eager loading the relationship we will essentially treat this
        // as a belongs-to style relationship since morph-to extends that class and
        // we will pass in the appropriate values so that it behaves as expected.
        else {
            $class = $this->getActualClassNameForMorph($class);

            $instance = new $class();

            return new MorphTo(
                $instance->newQuery(), $this, $id, $instance->getKeyName(), $type, $name
            );
        }
    }

    /**
     * Define a many-to-many relationship.
     *
     * @param string $related
     * @param string $collection
     * @param string $foreignKey
     * @param string $otherKey
     * @param string $relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function belongsToMany($related, $collection = null, $foreignPivotKey = null, $relatedPivotKey = NULL, $parentKey = NULL, $relatedKey = null, $relation = null)
    {
        // If no relationship name was passed, we will pull backtraces to get the
        // name of the calling function. We will use that function name as the
        // title of this relation since that is a great convention to apply.
        if (is_null($relation)) {
            $relation = $this->guessBelongsToManyRelation();
        }

        // Check if it is a relation with an original model.
        if (!is_subclass_of($related, \pierresilva\CouchDB\Eloquent\Model::class)) {
            return parent::belongsToMany($related, $collection, $foreignPivotKey, $relatedPivotKey, $parentKey, $relatedKey, $relation);
        }

        // First, we'll need to determine the foreign key and "other key" for the
        // relationship. Once we have determined the keys we'll make the query
        // instances as well as the relationship instances we need for this.
        $foreignPivotKey = $foreignPivotKey ?: $this->getForeignKey().'s';

        $instance = new $related();

        $relatedPivotKey = $relatedPivotKey ?: $instance->getForeignKey().'s';

        // If no table name was provided, we can guess it by concatenating the two
        // models using underscores in alphabetical order. The two model names
        // are transformed to snake case from their default CamelCase also.
        if (is_null($collection)) {
            $collection = $instance->getTable();
        }

        // Now we're ready to create a new query builder for the related model and
        // the relationship instances for the relation. The relations will set
        // appropriate query constraint and entirely manages the hydrations.
        $query = $instance->newQuery();

        return new BelongsToMany($query, $this, $collection, $foreignPivotKey, $relatedPivotKey, $parentKey, $relatedKey, $relation);
    }

    /**
     * Get the relationship name of the belongs to many.
     *
     * @return string
     */
    protected function guessBelongsToManyRelation()
    {
        if (method_exists($this, 'getBelongsToManyCaller')) {
            return $this->getBelongsToManyCaller();
        }

        return parent::guessBelongsToManyRelation();
    }
}
