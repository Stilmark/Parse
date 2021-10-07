# Parse

Web application utilities to parse data, input and output. Main classes:

- Request() - HTTP Request input parser
- Route() - Route web requests
- Str() - String manipulation and conversion
- Out() - Output formatter

## Request

```php
use Stilmark\Parse\Request;

Request::ip(); # Return remote ip address
Request::post('id'); # Return value of $_POST['id']
Request::get('id','name'); # Return array of $_GET['id'] and $_GET['name']
Request::post(); # Return entire array of $_POST
```

#### $_SERVER variables

| Method               | Variable                                              |
| -------------------- | ----------------------------------------------------- |
| Request::useragent() | $_SERVER['HTTP_USER_AGENT']                           |
| Request::referer()   | $_SERVER['HTTP_REFERER']                              |
| Request::method()    | $_SERVER['REQUEST_METHOD']                            |
| Request::uri()       | $_SERVER['REQUEST_URI']                               |
| Request::ip()        | $_SERVER['REMOTE_ADDR']                               |
| Request::host()      | $_SERVER['SERVER_NAME']                               |
| Request::locale()    | $_SERVER['HTTP_ACCEPT_LANGUAGE']                      |
| Request::country()   | $_SERVER['HTTP_CF_IPCOUNTRY'] * Cloudflare proxy only |
| Request::time()      | $_SERVER['REQUEST_TIME']                              |
| Request::lang()      | $_SERVER['LANGUAGE']                                  |

$_GLOBAL variables

| Method             | Variable    |
| ------------------ | ----------- |
| Request::get()     | $_GET       |
| Request::get('id') | $_GET['id'] |
| Request::post()    | $_POST      |
| Request::cookie()  | $_COOKIE    |

## Out(put)

```php
use Stilmark\Parse\Out;

echo Out::json( Request::get() );
```

## Str(ing)

    use Stilmark\Parse\Str;
