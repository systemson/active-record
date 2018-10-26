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
        $dbname = 'amber';

        $config = [
            'database' => [
                'driver' => 'pgsql',
                'host' => 'localhost',
                'port' => '5432',
                'user' => 'postgres',
                'password' => 'postgres',
            ],
        ];

        Config::set('active_record', $config);

        $this->assertTrue((new Database($dbname))->create());

        $config['database']['dbname'] = $dbname;

        Config::set('active_record', $config);
    }

    public function testTable()
    {
    }

    /*public function testRecords()
    {
        //$model = new Model();

        $model = Model::where('codigo', '=', 1);

        var_dump($model->get());
    }*/

    public function tearDown()
    {
        $config = Config::get('active_record'); 

        unset($config['database']['dbname']);

        Config::set('active_record', $config);

        // After updating amber/common should be
        //Config::unset('active_record.database.dbname');

        $this->assertTrue((new Database('amber'))->drop());
    }
}
