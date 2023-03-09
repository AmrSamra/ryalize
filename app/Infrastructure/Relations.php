<?php

namespace App\Infrastructure;

abstract class Relations
{
    protected string $primaryKey = 'id';
    protected string $foreignKey;

    /**
     * hasOne Relations constructor.
     * @param string $class
     * @return object
     */
    public function hasOne(string $class): object
    {
        $related = new $class();
        return $related->where(
            $this->foreignKey,
            $this->{$this->primaryKey}
        )->latest();
    }

    /**
     * hasMany Relations constructor.
     * @param string $class
     * @return object
     */
    public function hasMany(string $class): object
    {
        $related = new $class();
        return $related->where(
            $this->foreignKey,
            $this->{$this->primaryKey}
        )->latest();
    }

    /**
     * belongsTo Relations constructor.
     * @param string $class
     * @return object
     */
    public function belongsTo(string $class): object
    {
        $related = new $class();
        return $related->where(
            $related->primaryKey,
            $this->{$related->foreignKey}
        )->latest();
    }

    /**
     * belongsToMany Relations constructor.
     * @param string $class
     * @return object
     */
    public function belongsToMany(string $class): object
    {
        $related = new $class();
        return $related->where(
            $related->primaryKey,
            $this->{$related->foreignKey}
        )->latest();
    }
}
