<?php

namespace Tests;

use Amber\Gemstone\Provider\Provider;
use Amber\Collection\Collection;
use Tests\Example\MediatorMock;
use PHPUnit\Framework\TestCase;

class GemstoneTest extends TestCase
{
    public function testProvider()
    {
        $provider = new Provider(new MediatorMock());

        $new = $provider->new();

        //$this->assertFalse($new->isValid());

        //$this->assertEquals('default', $new->username);

        //$new->username = 'username';
        //$new->password = 'secret';

        $this->assertTrue($new->isValid());

        //$this->assertEquals('username', $new->username);
        //$this->assertEquals('secret', $new->password);

        //$resource = $provider->find(1);

        //$this->assertEquals('mocked_name', $resource->username);
        //$this->assertEquals('mocked_pass', $resource->password);
        //$this->assertEquals(true, $resource->status);
    }
}
