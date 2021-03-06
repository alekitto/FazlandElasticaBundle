<?php

/**
 * This file is part of the FazlandElasticaBundle project.
 *
 * (c) Tim Nagel <tim@nagel.com.au>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Fazland\ElasticaBundle\Tests\Functional;

use Elastica\Query\Match;

/**
 * @group functional
 */
class PropertyPathTest extends WebTestCase
{
    public function testContainerSource()
    {
        $client = $this->createClient(['test_case' => 'ORM']);
        /** @var \Fazland\ElasticaBundle\Persister\ObjectPersister $persister */
        $persister = $client->getContainer()->get('fazland_elastica.object_persister.index.property_paths_type');
        $obj = new TypeObj();
        $obj->coll = 'Hello';
        $persister->insertOne($obj);

        /** @var \Elastica\Index $elClient */
        $index = $client->getContainer()->get('fazland_elastica.index.index');
        $index->flush();

        $query = new Match();
        $query->setField('something', 'Hello');
        $search = $index->createSearch($query);

        $this->assertEquals(1, $search->count());
    }

    protected function setUp()
    {
        parent::setUp();

        $this->deleteTmpDir('Basic');
    }

    protected function tearDown()
    {
        parent::tearDown();

        $this->deleteTmpDir('Basic');
    }
}
