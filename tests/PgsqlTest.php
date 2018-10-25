<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Amber\Model\Drivers\DB;
use Amber\Model\Drivers\Model;
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
                'dbname' => 'systemson-erp',
                'user' => 'deivi',
                'password' => 'deivi',
            ],
        ];

        Config::set('active_record', $config);
    }

    public function testConnector()
    {
        $result = DB::query(
            'SELECT * FROM db.clientes WHERE codigo = :where_codigo',
            [':where_codigo' => 1]
        );

        /*var_dump(
            $result[0]->codigo,
            $result[0]->get('codigo'),
            $result[0]['codigo'],
            $result[0]->codigo = '2'
        );*/
    }

    public function testModel()
    {
        //$model = new Model();

        $model = Model::where('codigo', '=', 1);

        var_dump($model->get());
    }
}
