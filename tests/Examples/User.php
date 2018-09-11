<?php

namespace Tests\Examples;

use Amber\Model\ActiveRecord;

class User extends ActiveRecord
{
	protected $table = 'users';

	protected $primarykey = 'id';

	protected $columns = [
		'user' 			=> 'string(20)',
		'password'	 	=> 'string(250)',
		'created_at' 	=> 'date',
		'edited_at' 	=> 'date',
	];

	protected $attributes;

	protected $original;

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
}