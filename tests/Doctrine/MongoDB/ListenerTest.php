<?php

namespace Fazland\ElasticaBundle\Tests\Doctrine\MongoDB;

use Fazland\ElasticaBundle\Tests\Doctrine\ListenerTest as BaseListenerTest;

class ListenerTest extends BaseListenerTest
{
    public function setUp()
    {
        if (! class_exists('Doctrine\ODM\MongoDB\DocumentManager')) {
            $this->markTestSkipped('Doctrine MongoDB ODM is not available.');
        }
    }

    protected function getClassMetadataClass()
    {
        return 'Doctrine\ODM\MongoDB\Mapping\ClassMetadata';
    }

    protected function getLifecycleEventArgsClass()
    {
        return 'Doctrine\ODM\MongoDB\Event\LifecycleEventArgs';
    }

    protected function getListenerClass()
    {
        return 'Fazland\ElasticaBundle\Doctrine\Listener';
    }

    protected function getObjectManagerClass()
    {
        return 'Doctrine\ODM\MongoDB\DocumentManager';
    }
}
