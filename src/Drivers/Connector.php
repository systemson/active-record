<?php

namespace Amber\Model\Drivers;

use PDO;
use Amber\Config\Config;
use Amber\Utils\Implementations\AbstractSingleton;
use Amber\Utils\Traits\SingletonTrait;
use Amber\Model\Contracts\ConnectorInterface;

class Connector //extends AbstractSingleton //implements ConnectorInterface
{
	use SingletonTrait;

	public $driver;
	public $host;
	public $port;
	public $dbname;
	public $user;
	public $password;

	public function getDriver()
	{
		return 'pgsql';
	}

	public function getHost()
	{
		return 'localhost';
	}

	public function getPort()
	{
		return '5432';
	}

	public function getDbname()
	{
		return 'systemson-erp';
	}

	public function getUser()
	{
		return 'postgres';
	}

	public function getPassword()
	{
		return 'postgres';
	}

	public function connect()
	{
		$conn = "{$this->getDriver()}:host={$this->getHost()};port={$this->getPort()};dbname={$this->getDbname()};user={$this->getUser()};password={$this->getPassword()}";

		$pdo = new PDO($conn);

		return $pdo;
	}

	public static function __callStatic($method, $args = [])
	{
		call_user_func_array([self::getInstance(), $method], $args);
	}
}