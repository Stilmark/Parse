<?php

namespace Stilmark\Parse\tests\units;

// ./vendor/bin/atoum -f /www/stilmark/Parse/tests/units/Request.php

use atoum;
use Stilmark\Parse;

class Request extends atoum
{

    public function testSeverVars ()
    {

        $Instance = $this->newTestedInstance;

        $path = '/manual/en/reserved.variables.server.php';
        $query = 'return=true';

        $_SERVER['HTTPS'] = true;
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 11_2_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.90 Safari/537.36';
        $_SERVER['SERVER_NAME'] = 'www.php.net';
        $_SERVER['REQUEST_URI'] = $path . '?' . $query;
        $_SERVER['REMOTE_ADDR'] = '1.1.1.1';
        $_SERVER['HTTP_ACCEPT_LANGUAGE'] = 'da-DK';
        $_SERVER['HTTP_CF_IPCOUNTRY'] = 'DK';
        $_SERVER['HTTP_REFERER'] = 'https://www.php.net/manual/en/reserved.variables.php';

        $this->
            string($Instance::useragent())->isEqualTo($_SERVER['HTTP_USER_AGENT'])->
            string($Instance::method())->isEqualTo($_SERVER['REQUEST_METHOD'])->
            string($Instance::scheme())->isEqualTo('https')->
            string($Instance::host())->isEqualTo($_SERVER['SERVER_NAME'])->
            string($Instance::ip())->isEqualTo($_SERVER['REMOTE_ADDR'])->
            string($Instance::uri())->isEqualTo($_SERVER['REQUEST_URI'])->
            string($Instance::path())->isEqualTo($path)->
            string($Instance::query())->isEqualTo($query)->
            string($Instance::locale())->isEqualTo($_SERVER['HTTP_ACCEPT_LANGUAGE'])->
            string($Instance::country())->isEqualTo($_SERVER['HTTP_CF_IPCOUNTRY'])->
            string($Instance::referer())->isEqualTo($_SERVER['HTTP_REFERER'])
        ;

    }
}