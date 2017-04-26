<?php

namespace Fazland\ElasticaBundle\Exception;

class InvalidArgumentTypeException extends \InvalidArgumentException implements ExceptionInterface
{
    /**
     * @param string $value
     * @param int    $expectedType
     */
    public function __construct($value, $expectedType)
    {
        parent::__construct(sprintf('Expected argument of type "%s", "%s" given', $expectedType, is_object($value) ? get_class($value) : gettype($value)));
    }
}
