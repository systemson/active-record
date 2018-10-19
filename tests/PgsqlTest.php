<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Amber\Model\Drivers\Connector;
use Amber\Collection\Collection;
use PDO;

class PgsqlTest extends TestCase
{
    public function testConnector()
    {
        $conn = (new Connector())->connect();

        $query = $conn->prepare('SELECT * FROM db.clientes WHERE codigo = :codigo');
        $query->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, Collection::class);
        $query->execute([':codigo' => 1]);

        $result = new Collection($query->fetchAll());

        var_dump(
            $result[0]->codigo,
            $result[0]->get('codigo'),
            $result[0]['codigo'],
            $result[0]->codigo = '2'
        );
    }
}
