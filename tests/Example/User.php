<?php

namespace Tests\Example;

use Amber\Gemstone\Model\Model;

class User extends Model
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