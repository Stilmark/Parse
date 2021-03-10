<?php

namespace Stilmark\Parse\tests\units;

// include_once '/www/stilmark/Parse/src/Request.php';

// ./vendor/bin/atoum -f /www/stilmark/Parse/tests/units/Request.php

use atoum;
use Stilmark\Parse;

class Request extends atoum
{

    public function testHost ()
    {
		$_SERVER['SERVER_NAME'] = 'www.php.net';

        $this->given($this->newTestedInstance)
            ->then
				->string($this->testedInstance::host())
				->isEqualTo('www.php.net')
        ;
    }

    public function testIp ()
    {
        $_SERVER['REMOTE_ADDR'] = '1.1.1.1';

        $this->given($this->newTestedInstance)
            ->then
				->string($this->testedInstance::ip())
				->isEqualTo('1.1.1.1')
        ;
    }

// {"vars":{"scheme":"http","host":"www.php.net","path":"\/manual\/en\/reserved.variables.server.php","query":"boo=12","useragent":null,"referer":null,"method":null,"uri":"\/manual\/en\/reserved.variables.server.php?boo=12","ip":null,"locale":null,"country":null,"get":{"my":"nixeness"}}}

}