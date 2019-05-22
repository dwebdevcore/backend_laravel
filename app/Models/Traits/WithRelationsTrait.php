<?php

namespace App\Models\Traits;

trait WithRelationsTrait
{
    public function hasManyExists($related, $foreignKey = null, $localKey = null)
    {
        $foreignKey = $foreignKey ?: $this->getForeignKey();

        return $this->hasOne($related, $foreignKey, $localKey)
            ->setEagerLoads([])
            ->selectRaw($foreignKey)
            ->groupBy($foreignKey);
    }

    public function getExistsAttributeValue($relation)
    {
        $related = $this->getRelationValue($relation);

        return $related ? true : false;
    }

    public function hasManyAvg($column, $related, $foreignKey = null, $localKey = null)
    {
        $foreignKey = $foreignKey ?: $this->getForeignKey();

        return $this->hasOne($related, $foreignKey, $localKey)
            ->setEagerLoads([])
            ->selectRaw('`' . $foreignKey . '`' . ', AVG(`' . $column . '`) as aggregate')
            ->groupBy($foreignKey);
    }

    public function getAvgAttributeValue($relation)
    {
        $related = $this->getRelationValue($relation);

        return $related ? (int) $related->aggregate : 0;
    }
}
