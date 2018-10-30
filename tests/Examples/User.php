<?php

namespace Tests\Examples;

use Amber\ActiveRecord\Model\Model;

class User extends Model
{
	protected $name = 'users';

	//protected $primary_key = 'id';

	protected $columns = [
		'username'   => 'string|size:20|unique',
		'password'   => 'string|size:255',
		'status' 	 => 'boolean|default:true',
		'created_at' => 'date',
		'edited_at'  => 'date',
	];

	protected $timestamps = true;
}