<?php

namespace Fazland\ElasticaBundle\Tests\Transformer\ModelToElasticaIdentifierTransformer;

use Fazland\ElasticaBundle\Transformer\ModelToElasticaIdentifierTransformer;
use Symfony\Component\PropertyAccess\PropertyAccess;

class POPO
{
    protected $id = 123;
    protected $name = 'Name';

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }
}

class ModelToElasticaIdentifierTransformerTest extends \PHPUnit_Framework_TestCase
{
    public function testGetDocumentWithIdentifierOnly()
    {
        $transformer = $this->getTransformer();
        $document    = $transformer->transform(new POPO(), []);
        $data        = $document->getData();

        $this->assertInstanceOf('Elastica\Document', $document);
        $this->assertEquals(123, $document->getId());
        $this->assertCount(0, $data);
    }

    public function testGetDocumentWithIdentifierOnlyWithFields()
    {
        $transformer = $this->getTransformer();
        $document    = $transformer->transform(new POPO(), ['name' => []]);
        $data        = $document->getData();

        $this->assertInstanceOf('Elastica\Document', $document);
        $this->assertEquals(123, $document->getId());
        $this->assertCount(0, $data);
    }

    /**
     * @return ModelToElasticaIdentifierTransformer
     */
    private function getTransformer()
    {
        $typeMock = $this->getMockBuilder('Elastica\Type')
            ->disableOriginalConstructor()
            ->getMock();

        $transformer = new ModelToElasticaIdentifierTransformer($typeMock, ['identifier' => 'id']);
        $transformer->setPropertyAccessor(PropertyAccess::createPropertyAccessor());

        return $transformer;
    }
}
