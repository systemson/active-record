<?php

namespace Tests;

use Amber\Model\MigrationHandler;
use Amber\Model\Drivers\JsonDriver;
use Tests\Examples\User;
use PHPUnit\Framework\TestCase;

class JsonTest extends TestCase
{
    public function testJsonMigration()
    {
        $migrator = new MigrationHandler();
        $model = new User();

        $migrator->setDriver('json');

        $this->assertFalse($migrator->hasTable($model));

        $this->assertTrue($migrator->createTable($model));

        $this->assertTrue($migrator->hasTable($model));
    }

    /**
     * @depends testJsonMigration
     */
    public function testJsonInsert()
    {
        $user = new User();

        $user->user = 'deivi';
        $user->password = 'secret';

        $this->assertTrue($user->isValid());

        $this->assertTrue($user->save());
    }

    /**
     * @depends testJsonMigration
     */
    public function testJsonSelect()
    {
        $user = User::last();

        $this->assertSame(1, $user->id);
        $this->assertSame('deivi', $user->user);
        $this->assertSame('secret', $user->password);
    }

    /**
     * @depends testJsonMigration
     */
    public function testJsonUpdate()
    {
        $user = User::find(1);

        $user->user = 'systemson';

        $this->assertTrue($user->isValid());

        $this->assertTrue($user->save());
    }
}
