<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Amber\Model\Drivers\DB;
use Amber\Model\Drivers\Model;
use Amber\Model\Drivers\Database;
use Amber\Config\Config;
use Amber\Model\Config\ConfigAwareInterface;
use PDO;

class PgsqlTest extends TestCase
{
    public function setUp()
    {
        $config = [
            'database' => [
                'driver' => 'pgsql',
                'host' => 'localhost',
                'port' => '5432',
                //'dbname' => 'systemson-erp',
                'user' => 'postgres',
                'password' => 'postgres',
            ],
        ];

        Config::set('active_record', $config);
    }

    public function testDatabase()
    {
        $dbname = 'amber';

        $db = new Database($dbname);

        //$this->assertFalse($db->exists());

        //$db->create();

        //$this->assertTrue($db->exists());

        $db->drop();

        //$this->assertFalse($db->exists());
        
    }

    /*public function testConnector()
    {

    }*/

    /*public function testModel()
    {
        //$model = new Model();

        $model = Model::where('codigo', '=', 1);

        var_dump($model->get());
    }*/
}
