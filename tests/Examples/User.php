<?php

namespace Tests\Examples;

use Amber\Model\Drivers\Model;

class User extends Model
{
	protected $name = 'users';

	//protected $primary_key = 'id';

	protected $table = [
		'user'        => 'string|size:20|unique)',
		'password'    => 'string',
		'status' 	  => 'boolean|default:true',
		'created_at'  => 'date',
		'edited_at'   => 'date',
	];

	protected $timestamps = true;

	/*----------------------------------------------------------*/
	/*------------MUST BE MOVED TO A BASE CLASS-----------------*/
	/*----------------------------------------------------------*/
	public function getTable(): string
	{
		return $this->table;
	}

	public function isValid(): bool
	{
		return true;
	}

	public function __set($name, $value)
	{
		$this->properties[$name] = $value;
	}

	public function __get($name)
	{
		return $this->properties[$name];
	}

	public function toArray()
	{
		return $this->properties;
	}

	public function toJson()
	{
		return json_encode($this->properties);
	}
}