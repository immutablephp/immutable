# Immutable/immutable

Provides immutable value objects and an immutable value bag, along with a base
immutable class for your own objects.

Documentation to be completed.

## Bag

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
