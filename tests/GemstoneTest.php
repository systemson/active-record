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

		$new = $provider->blank();

		$this->assertEquals('default', $new->username);

		$new->username = 'username';

		$this->assertEquals('username', $new->username);

		$resource = $provider->find(1);

		$this->assertEquals('mocked', $resource->username);
	}
}
