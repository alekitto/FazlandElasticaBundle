<?php

namespace Fazland\ElasticaBundle\Tests\Serializer;

use Fazland\ElasticaBundle\Serializer\Callback;

class CallbackTest extends \PHPUnit_Framework_TestCase
{
    public function testSerializerMustHaveSerializeMethod()
    {
        $callback = new Callback();
        $this->setExpectedException('RuntimeException', 'The serializer must have a "serialize" method.');
        $callback->setSerializer(new \stdClass());
    }

    public function testSetGroupsWorksWithValidSerializer()
    {
        $callback = new Callback();
        $serializer = $this->getMockBuilder('Symfony\Component\Serializer\Serializer')->disableOriginalConstructor()->getMock();
        $callback->setSerializer($serializer);

        $callback->setGroups(['foo']);
    }

    public function testSetGroupsFailsWithInvalidSerializer()
    {
        $callback = new Callback();
        $serializer = $this->getMockBuilder('Fazland\ElasticaBundle\Tests\Serializer\FakeSerializer')->setMethods(['serialize'])->getMock();
        $callback->setSerializer($serializer);

        $this->setExpectedException(
            'RuntimeException',
            'Setting serialization groups requires using "JMS\Serializer\Serializer" or '
                .'"Symfony\Component\Serializer\Serializer"'
        );

        $callback->setGroups(['foo']);
    }
}

class FakeSerializer
{
    public function serialize()
    {
    }
}
