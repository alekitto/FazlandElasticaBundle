<?php

namespace Fazland\ElasticaBundle\Tests\DataCollector;

use Fazland\ElasticaBundle\DataCollector\ElasticaDataCollector;

/**
 * @author Richard Miller <info@limethinking.co.uk>
 */
class ElasticaDataCollectorTest extends \PHPUnit_Framework_TestCase
{
    public function testCorrectAmountOfQueries()
    {
        /** @var $requestMock \PHPUnit_Framework_MockObject_MockObject|\Symfony\Component\HttpFoundation\Request */
        $requestMock = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
            ->disableOriginalConstructor()
            ->getMock();

        /** @var $responseMock \PHPUnit_Framework_MockObject_MockObject|\Symfony\Component\HttpFoundation\Response */
        $responseMock = $this->getMockBuilder('Symfony\Component\HttpFoundation\Response')
            ->disableOriginalConstructor()
            ->getMock();

        /** @var $loggerMock \PHPUnit_Framework_MockObject_MockObject|\Fazland\ElasticaBundle\Logger\ElasticaLogger */
        $loggerMock = $this->getMockBuilder('Fazland\ElasticaBundle\Logger\ElasticaLogger')
            ->disableOriginalConstructor()
            ->getMock();

        $totalQueries = rand();

        $loggerMock->expects($this->once())
            ->method('getNbQueries')
            ->will($this->returnValue($totalQueries));

        $elasticaDataCollector = new ElasticaDataCollector($loggerMock);
        $elasticaDataCollector->collect($requestMock, $responseMock);
        $this->assertEquals($totalQueries, $elasticaDataCollector->getQueryCount());
    }

    public function testCorrectQueriesReturned()
    {
        /** @var $requestMock \PHPUnit_Framework_MockObject_MockObject|\Symfony\Component\HttpFoundation\Request */
        $requestMock = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
            ->disableOriginalConstructor()
            ->getMock();

        /** @var $responseMock \PHPUnit_Framework_MockObject_MockObject|\Symfony\Component\HttpFoundation\Response */
        $responseMock = $this->getMockBuilder('Symfony\Component\HttpFoundation\Response')
            ->disableOriginalConstructor()
            ->getMock();

        /** @var $loggerMock \PHPUnit_Framework_MockObject_MockObject|\Fazland\ElasticaBundle\Logger\ElasticaLogger */
        $loggerMock = $this->getMockBuilder('Fazland\ElasticaBundle\Logger\ElasticaLogger')
            ->disableOriginalConstructor()
            ->getMock();

        $queries = ['testQueries'];

        $loggerMock->expects($this->once())
            ->method('getQueries')
            ->will($this->returnValue($queries));

        $elasticaDataCollector = new ElasticaDataCollector($loggerMock);
        $elasticaDataCollector->collect($requestMock, $responseMock);
        $this->assertEquals($queries, $elasticaDataCollector->getQueries());
    }

    public function testCorrectQueriesTime()
    {
        /** @var $requestMock \PHPUnit_Framework_MockObject_MockObject|\Symfony\Component\HttpFoundation\Request */
        $requestMock = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
            ->disableOriginalConstructor()
            ->getMock();

        /** @var $responseMock \PHPUnit_Framework_MockObject_MockObject|\Symfony\Component\HttpFoundation\Response */
        $responseMock = $this->getMockBuilder('Symfony\Component\HttpFoundation\Response')
            ->disableOriginalConstructor()
            ->getMock();

        /** @var $loggerMock \PHPUnit_Framework_MockObject_MockObject|\Fazland\ElasticaBundle\Logger\ElasticaLogger */
        $loggerMock = $this->getMockBuilder('Fazland\ElasticaBundle\Logger\ElasticaLogger')
            ->disableOriginalConstructor()
            ->getMock();

        $queries = [[
            'engineMS' => 15,
            'executionMS' => 10,
        ], [
            'engineMS' => 25,
            'executionMS' => 20,
        ]];

        $loggerMock->expects($this->once())
            ->method('getQueries')
            ->will($this->returnValue($queries));

        $elasticaDataCollector = new ElasticaDataCollector($loggerMock);
        $elasticaDataCollector->collect($requestMock, $responseMock);
        $this->assertEquals(40, $elasticaDataCollector->getTime());
    }

    public function testName()
    {
        $loggerMock = $this->getMockBuilder('Fazland\ElasticaBundle\Logger\ElasticaLogger')
            ->disableOriginalConstructor()
            ->getMock();

        $elasticaDataCollector = new ElasticaDataCollector($loggerMock);

        $this->assertEquals('elastica', $elasticaDataCollector->getName());
    }
}
