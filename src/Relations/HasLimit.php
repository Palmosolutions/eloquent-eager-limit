<?php

namespace Staudenmeir\EloquentEagerLimit\Relations;

trait HasLimit
{
    /**
     * Alias to set the "limit" value of the query.
     *
     * @param  int  $value
     * @return $this
     */
    public function take($value)
    {
        return $this->limit($value);
    }

    /**
     * Set the "limit" value of the query.
     *
     * @param  int  $value
     * @return $this
     */
    public function limit($value)
    {
        $this->query->groupLimit($value, $this->getExistenceCompareKey());

        return $this;
    }
}
