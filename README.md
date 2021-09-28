# Parse

Web application utilities to parse data, input and output. Main classes:

- Route() - Route web requests
- Request() - HTTP Request input parser
- Str() - String manipulation and conversion
- Out() - Output formatter

## Request

    use Stilmark\Parse\Request;

    Request::get('ip');

## Out(put)

    use Stilmark\Parse\Out;
    
    echo Out::json( Request::get() );

## Str(ing)

    use Stilmark\Parse\Str;
