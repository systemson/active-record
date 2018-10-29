<?php

namespace Tests;

use Amber\Config\Config;
use Amber\Model\Drivers\Model;
use Tests\Examples\User;
use Amber\Model\Drivers\Database;
use Amber\Model\Config\ConfigAwareInterface;
use PHPUnit\Framework\TestCase;
use PDO;

class PgsqlTest extends TestCase
{
    const DB_NAME = 'amber_project';
    const TABLE_NAME = 'users';

    public static function setUpBeforeClass()
    {
        $config = [
            'database' => [
                'driver' => 'pgsql',
                'host' => 'localhost',
                'port' => '5432',
                'dbname' => static::DB_NAME,
                'user' => 'deivi',
                'password' => 'deivi',
            ],
        ];

        Config::set('active_record', $config);

        Database::table(static::TABLE_NAME, function ($table) {
            $table->dropIfExists();
        });
    }

    public function testTable()
    {
        $create = Database::table(static::TABLE_NAME, function ($table) {
            $table->id();
            $table->string('username', 50)->unique();
            $table->string('password');
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->create();
        });

        $this->assertTrue($create);
    }

    public function testRecords()
    {
        // Creates a new record
        $user = new User();

        $user->username = 'username';
        $user->password = 'password';

        $this->assertTrue($user->save());

        // Counts records
        //$this->assertEquals(1, User::count());

        // Whether the record exists
        //$this->assertTrue(User::has(1));
        //$this->assertTrue(User::hasUserName('username'));
        //$this->assertTrue(User::hasPassword('password'));

        // Returns a record from db
        $user = User::find(1);

        $user->username = 'admin';
        $this->assertTrue($user->save());

        $this->assertFalse($user->save());

        $this->assertTrue($user->delete());

        return;
        $this->assertInstance(User::class, $user);

        $this->assertEquals(1, $user->id);
        $this->assertEquals('username', $user->username);
        $this->assertEquals('password', $user->password);
        $this->assertEquals(true, $user->status);
    }

    public static function tearDownAfterClass()
    {
        Database::table(static::TABLE_NAME, function ($table) {
            //$table->dropIfExists();
        });
    }
}
