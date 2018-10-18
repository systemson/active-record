<?php

namespace Tests;

use Amber\Model\MigrationHandler;
use Amber\Model\Drivers\ArrayDriver;
use Tests\Examples\User;
use PHPUnit\Framework\TestCase;

class ArrayTest extends TestCase
{
    public function testCreateDB()
    {
        $config = [
            'default_driver' => 'array',
        ];

        $engine = new MigrationHandler($config);
        $model = new User();

        $this->assertTrue($engine->createDB('array_database'));

        $this->assertFalse($engine->hasTable($model));

        $this->assertTrue($engine->createTable($model));

        $this->assertTrue($engine->hasTable($model));

        return $engine;
    }

    /**
     * @depends testCreateDB
     */
    public function testInsert($engine)
    {
        $user = new User();

        $user->user = 'deivi';
        $user->password = 'secret';

        $this->assertTrue($user->isValid());

        $this->assertTrue($engine->save($user));
    }

    /**
     * @depends testCreateDB
     */
    public function testSelect()
    {
        $user = User::last();

        $this->assertSame(1, $user->id);
        $this->assertSame('deivi', $user->user);
        $this->assertSame('secret', $user->password);
    }

    /**
     * @depends testCreateDB
     */
    public function testJsonUpdate()
    {
        $user = User::find(1);

        $user->user = 'systemson';

        $this->assertTrue($user->isValid());

        $this->assertTrue($user->save());
    }
}
