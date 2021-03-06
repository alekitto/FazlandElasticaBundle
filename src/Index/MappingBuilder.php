<?php

/**
 * This file is part of the FazlandElasticaBundle project.
 *
 * (c) Infinite Networks Pty Ltd <http://www.infinite.net.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fazland\ElasticaBundle\Index;

use Fazland\ElasticaBundle\Configuration\IndexConfig;
use Fazland\ElasticaBundle\Configuration\TypeConfig;

class MappingBuilder
{
    /**
     * Skip adding default information to certain fields.
     *
     * @var array
     */
    private $skipTypes = ['completion'];

    /**
     * Builds mappings for an entire index.
     *
     * @param IndexConfig $indexConfig
     *
     * @return array
     */
    public function buildIndexMapping(IndexConfig $indexConfig)
    {
        $typeMappings = [];
        foreach ($indexConfig->getTypes() as $typeConfig) {
            $typeMappings[$typeConfig->getName()] = $this->buildTypeMapping($typeConfig);
        }

        $mapping = [];
        if (! empty($typeMappings)) {
            $mapping['mappings'] = $typeMappings;
        }

        $settings = $indexConfig->getSettings();
        if (! empty($settings)) {
            $mapping['settings'] = $settings;
        }

        return $mapping;
    }

    /**
     * Builds mappings for a single type.
     *
     * @param TypeConfig $typeConfig
     *
     * @return array
     */
    public function buildTypeMapping(TypeConfig $typeConfig)
    {
        $mapping = $typeConfig->getMapping();

        if (null !== $typeConfig->getDynamicDateFormats()) {
            $mapping['dynamic_date_formats'] = $typeConfig->getDynamicDateFormats();
        }

        if (null !== $typeConfig->getDateDetection()) {
            $mapping['date_detection'] = $typeConfig->getDateDetection();
        }

        if (null !== $typeConfig->getNumericDetection()) {
            $mapping['numeric_detection'] = $typeConfig->getNumericDetection();
        }

        if ($typeConfig->getAnalyzer()) {
            $mapping['analyzer'] = $typeConfig->getAnalyzer();
        }

        if ($typeConfig->getDynamic() !== null) {
            $mapping['dynamic'] = $typeConfig->getDynamic();
        }

        if (isset($mapping['dynamic_templates']) && empty($mapping['dynamic_templates'])) {
            unset($mapping['dynamic_templates']);
        }

        $this->fixProperties($mapping['properties']);
        if (! $mapping['properties']) {
            unset($mapping['properties']);
        }

        if (null !== $typeConfig->getModel()) {
            $mapping['_meta']['model'] = $typeConfig->getModel();
        }

        unset($mapping['_parent']['identifier'], $mapping['_parent']['property']);

        if (empty($mapping)) {
            // Empty mapping, we want it encoded as a {} instead of a []
            $mapping = new \ArrayObject();
        }

        return $mapping;
    }

    /**
     * Fixes any properties and applies basic defaults for any field that does not have
     * required options.
     *
     * @param $properties
     */
    private function fixProperties(&$properties)
    {
        if (null === $properties) {
            $properties = [];
        }

        foreach ($properties as $name => &$property) {
            unset($property['property_path']);

            if (! isset($property['type'])) {
                $property['type'] = 'text';
            }

            if (isset($property['fields'])) {
                $this->fixProperties($property['fields']);
            }

            if (isset($property['properties'])) {
                $this->fixProperties($property['properties']);
            }

            if (in_array($property['type'], $this->skipTypes)) {
                continue;
            }
        }
    }
}
