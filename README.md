# immutablephp/immutable

Provides truly immutable value objects and an immutable value bag, along with
base _Immutable_ and _ValueObject_ classes for your own objects. It helps to
prevent against the oversights described by the article
[Avoiding Quasi-Immutable Objects in PHP](http://paul-m-jones.com/archives/6400).

## Overview

The base _Immutable_ class protects against common oversights in PHP regarding
immutables:

- It defines `final public function __set()` and `final public function __unset()`
  to prevent adding and mutating undefined properties.

- It defines `final public function offsetSet()` and `final public function offsetUnset()`
  to prevent adding and mutating values via _ArrayAccess_.

- It prevents multiple calls to `__construct()` to re-initialize the object
  properties.

Further, the base _ValueObject_ class `with()` method checks the types of all
incoming values to make sure they are themselves immutable. It does so via the
static methods on the _Type_ class.

The _Type_ class recognizes scalars and nulls as immutable. All other non-object
values (such are resources and arrays) are rejected as mutable.

When it comes to objects, the _Type_ class recognizes anything descended from
_Immutable_ as immutable, as well as _DateTimeImmutable_. To allow _Type_ to
recognize other immutable classes, call `Type::register()` with a variadic list
of fully-qualified class names that you want to treat as immutable.

## Making Your Own Immutable Value Objects

> Note:
>
> This package can only do so much to keep you from accidentally overlooking
> mutability. For example, The _Immutable_ and _ValueObject_ classes cannot
> prevent you from deliberately adding your own mutable behaviors.  Likewise, it
> is not possible to prevent against using reflection to mutate an object from
> the outside.

To create your own immutable value object, extend _ValueObject_ with your own
properties.

```php
use Immutable\ValueObject\ValueObject;

class Address extends ValueObject
{
    protected $street;
    protected $city;
    protected $region;
    protected $postcode;
}
```

Then add a `with*()` method to allow changing of those values on a clone of the
object, using the protected `with()` method on the base ValueObject.

```php
class Address extends ValueObject
{
    protected $street;
    protected $city;
    protected $region;
    protected $postcode;

    public function withChanged(
        string $street,
        string $city,
        string $region,
        string $postcode
    ) {
        return $this->with([
            'street' => $street,
            'city' => $city,
            'region' => $region,
            'postcode' => $postcode
        ]);
    }
}
```

Finally, use that method in the constructor to initialize the properties, and
call `parent::__construct()` to finish initialization.

```php
class Address extends ValueObject
{
    protected $street;
    protected $city;
    protected $region;
    protected $postcode;

    public function __construct(
        string $street,
        string $city,
        string $region,
        string $postcode
    ) {
        $this->withChanged($street, $city, $state, $zip);
        parent::__construct();
    }

    public function withChanged(
        string $street,
        string $city,
        string $region,
        string $postcode
    ) : self
    {
        return $this->with([
            'street' => $street,
            'city' => $city,
            'region' => $region,
            'postcode' => $postcode
        ]);
    }
}
```

> Warning:
>
> If you do not call `parent::__construct()` then the Value Object will not know
> that it has been initialized, and it will be possible to call the constructor
> multiple time to re-initialize the object.

Now you have an immutable Value Object.

You may find it useful to add validation; do so in your `with*()` methods,
either directly or by calling a validation mechanism.

```php
    public function withChanged(
        string $street,
        string $city,
        string $region,
        string $postcode
    ) {

        $valid = AddressValidator::validate($street, $city, $region, $postcode);
        if (! $valid) {
            throw new \RuntimeException('address is not valid');
        }

        return $this->with([
            'street' => $street,
            'city' => $city,
            'region' => $region,
            'postcode' => $postcode
        ]);
    }
```


## Provided Immutable Value Objects

This package provides several Value Objects, both as examples and for common
usage.

### CreditCard

```php
use Immutable\ValueObject\CreditCard;

$creditCard = new CreditCard('5555-5555-5555-4444');

// reading
$creditCard->getNumber(); // '5555555555554444'
$creditCard->getBrand(); // 'VISA'

// changing
$newCreditCard = $creditCard->withNumber('4111-1111-1111-1111');
$newCreditCard->getNumber(); // '4111111111111111'
$newCreditCard->getBrand(); // 'MASTERCARD'
```

### Email

```php
use Immutable\ValueObject\Email;

$email = new Email('bolivar@example.com');

// reading
$email->get(); // 'bolivar@example.com'

// changing
$newEmail = $email->withAddress('boshag@example.net');
$newEmail->get(); // 'boshag@example.net'
```

### Ip

```php
use Immutable\ValueObject\Ip;

$ip = new Ip('127.0.0.1');

// reading
$ip->get(); // '127.0.0.1'

// changing
$newIp = $ip->withAddress('192.168.0.1');
$newIp->get(); // '192.168.0.1'
```

### Isbn

```php
use Immutable\ValueObject\Isbn;

$isbn = new Isbn('960-425-059-0');

// reading
$isbn->get(); // '960-425-059-0'

// changing
$newIsbn = $ip->withAddress('0-8044-2957-X');
$newIsbn->get(); // '0-8044-2957-X'
```

### Uri\HttpUri

```php
use Immutable\ValueObject\Uri\HttpUri;

$httpUri = new HttpUri(
    'http://boshag:bopass@example.com:8080/foo?bar=baz#dib'
);

// reading
$httpUri->getScheme(); // 'http'
$httpUri->getHost(); // 'example.com'
$httpUri->getPort(); // 8080
$httpUri->getUser(); // 'boshag'
$httpUri->getPass(); // 'bopass'
$httpUri->getPath(); // /'foo'
$httpUri->getQuery(); // 'bar=baz'
$httpUri->getFragment(); // 'dib'

// changing
$newHttpUri = $httpUri
    ->withScheme('https')
    ->withHost('example.net')
    ->withPort('8888')
    ->withUser('newuser')
    ->withPass('newpass')
    ->withPath('/foo2')
    ->withQuery('zim=gir')
    ->withFragment('irk');

$newHttpUri->get(); // 'https://newuser:newpass@example.net:8888/foo2/?zim=gir#irk'
```

### Uuid

```php
use Immutable\ValueObject\Uuid;

$uuid = new Uuid('12345678-90ab-cdef-1234-567890123456');

// reading
$uuid->get(); // '12345678-90ab-cdef-1234-567890123456'

// changing
$newUuid = $uuid->withIdentifier('11111111-1111-1111-1111-111111111111');
$newUuid->get(); // '11111111-1111-1111-1111-111111111111'

// create a new random UUIDv4 identifier
$uuidv4 = Uuid::newVersion4();
```

## Immutable Bag

The _Bag_ is for an arbitrary collection of immutable values, and can be useful
for immutable representations of JSON data.

```php
use Immutable\Bag;

$bag = new Bag(['foo' => 'bar']);
echo $bag->foo; // bar
echo $bag['foo']; // bar

$bag->foo = 'baz'; // ImmutableObjectException
$bag = $bag->with('foo', 'baz');
echo $bag->foo; // baz
echo $bag['foo']; // baz

unset($bag->foo); // ImmutableObjectException
$bag = $bag->without('foo');

$bag->dib; // Notice: $dib not defined

$bag = $bag->with('dib', ['zim', 'gir']);
foreach ($bag->dib as $key => $value) {
    echo "$key:$value,"; // 0:zim,1:gir,
}
```
