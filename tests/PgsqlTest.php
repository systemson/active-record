<?php

namespace Tests;

use Amber\Config\Config;
use Amber\ActiveRecord\Database\Database;
use Amber\ActiveRecord\Config\ConfigAwareInterface;
use PDO;
use PHPUnit\Framework\TestCase;
use Tests\Examples\User;

class PgsqlTest extends TestCase
{
    const DB_NAME = 'amber_project';
    const TABLE_NAME = 'users';
    const DRIVER_NAME = 'pgsql';

    public static function config($name)
    {
        $json = json_decode(file_get_contents(getcwd() . DIRECTORY_SEPARATOR . 'database.json'), true);

        return $json[static::DRIVER_NAME][$name] ?? null;
    }

    public static function setUpBeforeClass()
    {
        $config = [
            'database' => [
                'driver' => static::DRIVER_NAME,
                'host' => self::config('host'),
                'port' => self::config('port'),
                'dbname' => static::DB_NAME,
                'user' => self::config('user'),
                'password' => self::config('password'),
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

        // Checks that the returned value is a instance of the model class.
        $this->assertInstanceOf(User::class, $user);

        // Checks the values from table.
        $this->assertEquals(1, $user->id);
        $this->assertEquals('username', $user->username);
        $this->assertEquals('password', $user->password);
        $this->assertEquals(true, $user->status);

        // Sets a new username.
        $user->username = 'admin';

        // Persists in DB the edited record.
        $this->assertTrue($user->save());

        // Checks that the new name is in the DB
        $user = User::find(1);
        $this->assertEquals('admin', $user->username);

        // Returns false since there is nothing to update.
        $this->assertFalse($user->save());

        // Deletes the records in DB.
        $this->assertTrue($user->delete());

        return;
    }

    public static function tearDownAfterClass()
    {
        Database::table(static::TABLE_NAME, function ($table) {
            $table->dropIfExists();
        });
    }
}
