<?php

namespace Stilmark\Parse\tests\units;

// ./vendor/bin/atoum -f /www/stilmark/Parse/tests/units/Route.php

use atoum;
use Stilmark\Parse;
use Stilmark\Parse\Out;

class Route extends atoum
{

    public function testRouteGet ()
    {

        $Instance = $this->newTestedInstance;

        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['SERVER_NAME'] = 'www.php.net';
        $_SERVER['REQUEST_URI'] = '/api/users';

        $this->array($Instance::dispatch())->string['status']->isEqualTo('ok');

    }

}