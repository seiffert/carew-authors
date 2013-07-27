<?php

namespace Carew\Plugin\Authors\Tests;

use Carew\Carew;
use Carew\Plugin\Authors\AuthorsExtension;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @group unit
 */
class AuthorsExtensionTest extends \PHPUnit_Framework_TestCase
{
    /** @var  AuthorsExtension */
    private $extension;

    public function setUp()
    {
        $this->extension = new AuthorsExtension();
    }

    public function testRegisterAddsEventSubscribers()
    {
        $eventDispatcher = $this->createMockEventDispatcher();

        $eventDispatcher->expects($this->at(0))
            ->method('addSubscriber')
            ->with($this->isInstanceOf('Carew\Plugin\Authors\EventSubscriber\IndexesEventSubscriber'));

        $eventDispatcher->expects($this->at(1))
            ->method('addSubscriber')
            ->with($this->isInstanceOf('Carew\Plugin\Authors\EventSubscriber\AuthorsEventSubscriber'));

        $carew = $this->setCarewEventDispatcher(
            $this->setCarewContainer(
                $this->createMockCarew()
            ),
            $eventDispatcher
        );

        $this->extension->register($carew);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|Carew
     */
    private function createMockCarew()
    {
        return $this->getMock('Carew\Carew', array(), array(), '', false);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|EventDispatcherInterface
     */
    private function createMockEventDispatcher()
    {
        return $this->getMock('Symfony\Component\EventDispatcher\EventDispatcher');
    }

    /**
     * @param Carew $carew
     * @param EventDispatcherInterface $dispatcher
     * @return \PHPUnit_Framework_MockObject_MockObject|Carew
     */
    private function setCarewEventDispatcher(Carew $carew, EventDispatcherInterface $dispatcher = null)
    {
        if (null === $dispatcher) {
            $dispatcher = $this->createMockEventDispatcher();
        }

        $carew->expects($this->any())
            ->method('getEventDispatcher')
            ->will($this->returnValue($dispatcher));

        return $carew;
    }

    /**
     * @param Carew $carew
     * @param \Pimple $dispatcher
     * @return \PHPUnit_Framework_MockObject_MockObject|Carew
     */
    private function setCarewContainer(Carew $carew, \Pimple $container = null)
    {
        if (null === $container) {
            $container = $this->getMock('Pimple');
        }

        $carew->expects($this->any())
            ->method('getContainer')
            ->will($this->returnValue($container));

        return $carew;
    }
}
