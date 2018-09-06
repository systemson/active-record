<?php

namespace Tests;

use Amber\Model\Migration\JsonMigration;
use Amber\Model\Client\JsonClient;
use PHPUnit\Framework\TestCase;

class JsonClientTest extends TestCase
{
    public function testJsonMigration()
    {
        $email = new JsonMigration();
    }

    /**
     * @depends testJsonMigration
     */
    public function testJson()
    {
        $email = new JsonClient();
    }
}
