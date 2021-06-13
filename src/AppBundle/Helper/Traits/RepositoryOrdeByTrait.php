<?php

namespace AppBundle\Helper\Traits;

use Doctrine\ORM\QueryBuilder;

trait RepositoryOrdeByTrait
{
    /**
     * Repository order by query
     *
     * @param QueryBuilder $query
     * @param array $orderBy
     */
    protected function initOrderBy(QueryBuilder $query, array $orderBy = null)
    {
        if ($orderBy !== null) {
            foreach ($orderBy as $sort => $order) {
                $query->addOrderBy($query->getRootAliases()[0].'.'.$sort, $order);
            }
        }
    }

}