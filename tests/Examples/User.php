<?php

namespace Tests\Examples;

use Amber\Model\Drivers\Model;

class User extends Model
{
	protected $name = 'users';

	//protected $primary_key = 'id';

	protected $table = [
		'user'        => 'string|size:20|unique',
		'password'    => 'string',
		'status' 	  => 'boolean|default:true',
		'created_at'  => 'date',
		'edited_at'   => 'date',
	];

	protected $timestamps = true;
}