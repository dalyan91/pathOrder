<?php

namespace AppBundle\Helper\Traits;

use Doctrine\ORM\QueryBuilder;

trait RepositoryPaginationTrait
{
    /**
     * Repository pagination definitions
     *
     * @param QueryBuilder $query
     * @param int $limit
     * @param int $offset
     */
    protected function initPagination(QueryBuilder $query, $limit = null, $offset = 0)
    {
        if ($limit !== null) {
            $query->setFirstResult($offset);
            $query->setMaxResults($limit);
        }
    }

}