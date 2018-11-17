<?php

namespace Tests\Example;

use Amber\Gemstone\Contracts\ProviderContract;

class MediatorMock
{
	public function first($id)
	{
		return [
			'username' => 'mocked',
			'password' => 'secret',
			'created_at' => '2018-11-17',
			'edited_at' => null,
		];
		
	}
}