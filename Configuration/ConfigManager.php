<?php

/**
 * This file is part of the FazlandElasticaBundle project.
 *
 * (c) Tim Nagel <tim@nagel.com.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fazland\ElasticaBundle\Configuration;

/**
 * Central manager for index and type configuration.
 */
class ConfigManager implements ManagerInterface
{
    /**
     * @var IndexConfig[]
     */
    private $indexes = [];

    /**
     * @param string $indexName
     *
     * @return IndexConfig
     */
    public function getIndexConfiguration($indexName)
    {
        if (! $this->hasIndexConfiguration($indexName)) {
            throw new \InvalidArgumentException(sprintf('Index with name "%s" is not configured.', $indexName));
        }

        return $this->indexes[$indexName];
    }

    /**
     * {@inheritdoc}
     */
    public function getIndexNames()
    {
        return array_keys($this->indexes);
    }

    /**
     * {@inheritdoc}
     */
    public function getTypeConfiguration($indexName, $typeName)
    {
        $index = $this->getIndexConfiguration($indexName);
        $type = $index->getType($typeName);

        if (! $type) {
            throw new \InvalidArgumentException(sprintf('Type with name "%s" on index "%s" is not configured', $typeName, $indexName));
        }

        return $type;
    }

    /**
     * @param string $indexName
     *
     * @return bool
     */
    public function hasIndexConfiguration($indexName)
    {
        return isset($this->indexes[$indexName]);
    }

    /**
     * Add an IndexConfig to the manager.
     *
     * @param IndexConfig $indexConfig
     */
    public function addIndexConfiguration(IndexConfig $indexConfig)
    {
        $this->indexes[$indexConfig->getName()] = $indexConfig;
    }
}
