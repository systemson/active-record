<?php

namespace Tests;

use Amber\Config\Config;
use Amber\Model\Drivers\Model;
use Amber\Model\Drivers\Database;
use Amber\Model\Config\ConfigAwareInterface;
use PHPUnit\Framework\TestCase;
use PDO;

class PgsqlTest extends TestCase
{
    protected $dbname = 'amber_project';
    protected $table = 'users';

    public function setUp()
    {
        $config = [
            'database' => [
                'driver' => 'pgsql',
                'host' => 'localhost',
                'port' => '5432',
                'dbname' => $this->dbname,
                'user' => 'deivi',
                'password' => 'deivi',
            ],
        ];

        Config::set('active_record', $config);

        Database::table($this->table, function ($table) {
            $table->dropIfExists();
        });
    }

    public function testTable()
    {
        $create = Database::table($this->table, function ($table) {
            $table->id();
            $table->string('username', 50)->unique();
            $table->string('password');
            $table->boolean('status')->default(true);
            $table->date('created_at');
            $table->date('edited_at');

            $table->create();
        });

        $this->assertTrue($create);
    }

    /*public function testRecords()
    {
        // Creates a new record
        $model = new Model();

        $model->username = 'username';
        $model->password = 'password';

        $this->assertTrue($model->save());

        // Counts records
        $this->assertEquals(1, Model::count());

        // Whether the record exists
        $this->assertTrue(Model::has(1));
        $this->assertTrue(Model::hasUserName('username'));
        $this->assertTrue(Model::hasPassword('password'));

        // Returns a record from db
        $user = Model::find(1);

        $this->assertInstance(Model::class, $user);

        $this->assertEquals(1, $user->id);
        $this->assertEquals('username', $user->username);
        $this->assertEquals('password', $user->password);
        $this->assertEquals(true, $user->status);
    }*/

    public function tearDown()
    {
        Database::table($this->table, function ($table) {
            //$table->dropIfExists();
        });
    }
}
