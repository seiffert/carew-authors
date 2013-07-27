<?php

namespace Carew\Plugin\Authors\Tests\EventSubscriber;

use Carew\Plugin\Authors\Events as AuthorEvents;
use Carew\Plugin\Authors\EventSubscriber\AuthorsEventSubscriber;

class AuthorsEventSubscriberTest extends \PHPUnit_Framework_TestCase
{
    public function testSubscribedEvents()
    {
        $this->assertArrayHasKey(AuthorEvents::AUTHORS, AuthorsEventSubscriber::getSubscribedEvents());
    }
}
