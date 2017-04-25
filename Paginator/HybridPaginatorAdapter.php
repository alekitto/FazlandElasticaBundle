<?php

namespace Fazland\ElasticaBundle\Paginator;

use Elastica\Query;
use Elastica\SearchableInterface;
use Fazland\ElasticaBundle\Transformer\ElasticaToModelTransformerInterface;

/**
 * Allows pagination of \Elastica\Query.
 */
class HybridPaginatorAdapter extends RawPaginatorAdapter
{
    private $transformer;

    /**
     * @param SearchableInterface                 $searchable  the object to search in
     * @param Query                               $query       the query to search
     * @param ElasticaToModelTransformerInterface $transformer the transformer for fetching the results
     */
    public function __construct(SearchableInterface $searchable, Query $query, ElasticaToModelTransformerInterface $transformer)
    {
        parent::__construct($searchable, $query);

        $this->transformer = $transformer;
    }

    /**
     * {@inheritdoc}
     */
    public function getResults($offset, $length)
    {
        return new HybridPartialResults($this->getElasticaResults($offset, $length), $this->transformer);
    }
}
