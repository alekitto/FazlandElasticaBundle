<?php

namespace Fazland\ElasticaBundle\Doctrine;

/**
 * Fetches a slice of objects.
 *
 * @author Thomas Prelot <tprelot@gmail.com>
 */
interface SliceFetcherInterface
{
    /**
     * Fetches a slice of objects using the query builder.
     *
     * @param object $queryBuilder
     * @param int    $limit
     * @param int    $offset
     * @param array  $previousSlice
     * @param array  $identifierFieldNames
     *
     * @return array
     */
    public function fetch($queryBuilder, $limit, $offset, array $previousSlice, array $identifierFieldNames);
}
