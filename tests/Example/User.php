<?php

namespace Tests\Example;

use Amber\Gemstone\Contracts\ProviderContract;

class UserProvider implements ProviderContract
{

    protected $name = 'users';

    protected $attributes = [
        'username' => 'string|unique|default=default|max=50|not_null',
        'password' => 'string|max=254|not_null',
        'status' => 'boolean|default=1|not_null',
        'created_at' => 'date=Y-m-d|default=2018-11-21|not_null',
        'edited_at' => 'date=Y-m-d',
    ];

    protected $id = 'id';

    protected $timestamps = true;
}
