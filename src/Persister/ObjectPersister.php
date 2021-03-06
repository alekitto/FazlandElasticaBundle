<?php

namespace Fazland\ElasticaBundle\Persister;

use Fazland\ElasticaBundle\Elastica\Type;

/**
 * Inserts, replaces and deletes single documents in an elastica type
 * Accepts domain model objects and converts them to elastica documents.
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
class ObjectPersister implements ObjectPersisterInterface
{
    protected $type;
    protected $objectClass;
    protected $logger;

    /**
     * @param Type   $type
     * @param string $objectClass
     */
    public function __construct(Type $type, $objectClass)
    {
        $this->type = $type;
        $this->objectClass = $objectClass;
    }

    /**
     * {@inheritdoc}
     */
    public function handlesObject($object)
    {
        return $object instanceof $this->objectClass;
    }

    /**
     * {@inheritdoc}
     */
    public function insertOne($object)
    {
        @trigger_error(sprintf('Use of %s is deprecated. Use persist method instead.', __METHOD__), E_USER_DEPRECATED);

        $this->persist($object);
    }

    /**
     * {@inheritdoc}
     */
    public function replaceOne($object)
    {
        @trigger_error(sprintf('Use of %s is deprecated. Use persist method instead.', __METHOD__), E_USER_DEPRECATED);

        $this->persist($object);
    }

    /**
     * {@inheritdoc}
     */
    public function deleteOne($object)
    {
        @trigger_error(sprintf('Use of %s is deprecated. Use unpersist method instead.', __METHOD__), E_USER_DEPRECATED);

        $this->unpersist($object);
    }

    /**
     * {@inheritdoc}
     */
    public function insertMany(array $objects)
    {
        @trigger_error(sprintf('Use of %s is deprecated. Use persist method instead.', __METHOD__), E_USER_DEPRECATED);

        $this->persist(...$objects);
    }

    /**
     * {@inheritdoc}
     */
    public function replaceMany(array $objects)
    {
        @trigger_error(sprintf('Use of %s is deprecated. Use persist method instead.', __METHOD__), E_USER_DEPRECATED);

        $this->persist(...$objects);
    }

    /**
     * {@inheritdoc}
     */
    public function deleteMany(array $objects)
    {
        @trigger_error(sprintf('Use of %s is deprecated. Use unpersist method instead.', __METHOD__), E_USER_DEPRECATED);

        $this->unpersist(...$objects);
    }

    /**
     * {@inheritdoc}
     */
    public function deleteManyByIdentifiers(array $identifiers)
    {
        @trigger_error(sprintf('Use of %s is deprecated. Use deleteById method instead.', __METHOD__), E_USER_DEPRECATED);

        $this->deleteById(...$identifiers);
    }

    /**
     * {@inheritdoc}
     */
    public function persist(...$objects)
    {
        $this->type->persist(...$objects);
    }

    /**
     * {@inheritdoc}
     */
    public function unpersist(...$objects)
    {
        $this->type->unpersist(...$objects);
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById(...$identifiers)
    {
        $this->type->deleteIds($identifiers);
    }
}
