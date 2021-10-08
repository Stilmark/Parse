# Parse

Web application utilities to parse data, input and output. Main classes:

- Request() - HTTP Request input parser
- Route() - Route web requests
- Str() - String manipulation and conversion
- Out() - Output formatter

## Request

HTTP Request input parser, which provides easy access to global variables via the static Request interface.

```php
use Stilmark\Parse\Request;

$_SERVER['REMOTE_ADDR'] = '127.0.0.1';

echo Request::ip(); 
// Return remote ip address '127.0.0.1'

$_SERVER = ['HTTPS' => true, 'SERVER_NAME' => 'example.com', 'REQUEST_URI' => '/search?offset=10'];

echo Request::url(); 
// Return full url 'https://example.com/search?offset=10'
echo Request::path(); 
// Return url path '/search'

$_POST = ['id' => 12, 'name' => 'Hans', 'age' => 50];

echo Request::post('id'); 
// Return value '12'
echo Out::json( Request::post('id', 'name') ); 
// Return array {"id":12,"name":"Hans"}
echo Out::json( Request::post() ); 
// Return complete array {"id":12,"name":"Hans","age":50}

```



#### Request $_SERVER

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

#### Request $GLOBAL

| Method             | Variable    |
| ------------------ | ----------- |
| Request::get()     | $_GET       |
| Request::get('id') | $_GET['id'] |
| Request::post()    | $_POST      |
| Request::cookie()  | $_COOKIE    |

#### Request URL

| Method          | Variable                                        |
| --------------- | ----------------------------------------------- |
| Request::url()  | Compiled variable of `SCHEME`, `HOST` and `URI` |
| Request::path() | URI without query string or fragment            |



## Route



## Out(put)

```php
use Stilmark\Parse\Out;

echo Out::json( Request::get() );
```

## Str(ing)

    use Stilmark\Parse\Str;
