<?php

namespace Tests\Example;

use Amber\Gemstone\Contracts\ProviderContract;

class UserProvider implements ProviderContract
{
	protected $name = 'users';

	//protected $primary_key = 'id';

	protected $columns = [
		'username'   => 'string|size:20|unique',
		'password'   => 'string|size:255',
		'status' 	 => 'boolean|default:true',
	];

	protected $timestamps = true;
}