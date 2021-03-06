<?php

namespace Fazland\ElasticaBundle\Doctrine\ORM;

use Doctrine\ORM\QueryBuilder;
use Fazland\ElasticaBundle\Doctrine\AbstractProvider;

class Provider extends AbstractProvider
{
    const ENTITY_ALIAS = 'a';

    public function count(int $offset = null, int $size = null)
    {
        /** @var QueryBuilder $qb */
        $qb = $this->createQueryBuilder($this->options['query_builder_method']);
        $qb->select('COUNT('.static::ENTITY_ALIAS.')');

        $count = $qb->getQuery()->getSingleScalarResult();

        if (null !== $offset) {
            $count -= $offset;
        }

        if (null !== $size) {
            $count = min($count, $size);
        }

        return $count;
    }

    public function provide(int $offset = null, int $size = null)
    {
        /** @var QueryBuilder $qb */
        $qb = $this->createQueryBuilder($this->options['query_builder_method']);

        if (null !== $offset) {
            $qb->setFirstResult($offset);
        }

        if (null !== $size) {
            $qb->setMaxResults($size);
        }

        $canIterate = empty($qb->getDQLPart('join'));

        $query = $qb->getQuery();
        if ($canIterate) {
            foreach ($query->iterate() as $result) {
                $object = $result[0];

                if (! $this->options['skip_indexable_check'] && ! $this->isIndexable($object)) {
                    continue;
                }

                yield $object;
            }
        } else {
            if ($this->options['skip_indexable_check']) {
                $result = $query->getResult();
            } else {
                $result = array_filter($query->getResult(), [$this, 'isIndexable']);
            }

            yield from $result;
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function createQueryBuilder($method, array $arguments = [])
    {
        $repository = $this->managerRegistry->getRepository($this->modelClass);

        // ORM query builders require an alias argument
        $arguments = [static::ENTITY_ALIAS] + $arguments;

        return $repository->{$method}(...$arguments);
    }
}
