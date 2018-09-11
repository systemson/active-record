<?php

namespace Tests;

use Amber\Model\DB\JsonEngine;
use Amber\Model\DB\JsonDB;
use Amber\Model\DB\JsonTable;
use Amber\Model\DB\Users; //Extends JsonItem
use PHPUnit\Framework\TestCase;

class JsonTest extends TestCase
{
    public function testCreate()
    {
        $user = new User();

        $json_db = new JsonBD();
        $json_db->name('json_db');
        $json_db->save($user);

        $engine = new JsonEngine();
        $engine->save();

        return $engine;
    }

    /**
     * @depends testCreate
     */
    public function testAddRecord($engine)
    {
        $user = new Users();
        $user->name = 'Deivi';
        $user->password = '1234';
        $user->validate();

        $user->save();

        $user->password = 'secret';

        $user->save();
    }

    /**
     * @depends testCreate
     */
    public function testGetRecord($engine)
    {
        $users = $engine->getTable('users');

        $deivi = $users->find(1);

        $deivi->name = 'Deivi';
        $deivi->password = 'secret';

        $again = $users->get(['id' => 1]);

        $deivi->name = 'Deivi';
        $deivi->password = 'secret';
    }

    /**
     * @depends testAddRecord
     */
    public function testDrop($engine)
    {
        $engine->dropRecord(['name' => 'Deivi']);
        $engine->dropTable('users');
        $engine->dropDB('json_db');
    }
}
