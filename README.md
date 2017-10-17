# Laravel Persisters

[![Build Status](https://travis-ci.org/GeorgeHanson/laravel-persisters.svg?branch=master)](https://travis-ci.org/georgehanson/laravel-persisters) [![Coverage](https://codecov.io/gh/georgehanson/laravel-persisters/branch/master/graph/badge.svg)](https://codecov.io/gh/georgehanson/laravel-persisters)

This package is designed to make it easy to create a persister class for your laravel project. The idea of persisters is to abstract the data storing process.

## Installation

To install the package, simply add the following to your composer.json file:

```json
require: {
    ...
    "georgehanson/laravel-persisters": "^1.0"
    ...
}
```

## Usage

### Creating Persisters

To create a persister you simply need to create a new class, have it extend the base persister class and implement the abstracted methods. Here is an example:

```php

use GeorgeHanson\LaravelPersisters\BasePersister;

class MyPersister extends BasePersister
{
    /**
     * Create a new Model
     *
     * @param array $data
     * @return Model
     */
    protected function create(array $data)
    {
        // Store a new resource here
    }

    /**
     * Update the given Model
     *
     * @param array $data
     * @param Model $model
     * @return Model
     */
    protected function update(array $data, Model $model)
    {
        // Update the given model here
    }
}
```

In order to use your new persister, you can simply instantiate the class and call the persist method. Below is an example:

```php

$data = [
    'first_name' => 'John',
    'last_name' => 'Doe'
];

$persister = new MyPersister();

$persister->persist($data);

```

The base persister class will automatically work out whether you are creating a record or updating a record.

### Creating Records

To create a new record using your persister class, simply call the persist method and pass in the data you which to save. The persist method can either accept an array of data, or a class which is `Arrayable` (such as a Collection, Request). Here is an example of creating a resource from a request.

```php

use Illuminate\Http\Request;
use App\Persisters\MyPersister;

class MyController extends Controller
{
    public function store(Request $request, MyPersister $persister) 
    {
        $record = $persister->persist($request);
    }
}

```

This will the fire the `create` method within your persister class you have created. Here you can handle any logic you wish for creating the resource.

### Updating Records

Updating records is just as simple as creating records. The only difference is you have to pass a second parameter to the `persist` method which is the model you want to update. Below is an example of how you would update a record from a request.

```php

use Illuminate\Http\Request;
use App\Persisters\MyPersister;
use App\User;

class MyController extends Controller
{
    public function update($id, Request $request, MyPersister $persister) 
    {
        $user = User::find($id);
        
        // Update the user with the given data
        $record = $persister->persist($request, $user);
    }
}

```

### Filtering Data

We cannot be certain that the data we receive in our request is always the data we want to persist. For example, when we are saving the record we do not want to store the `_token` which is passed by Laravel for CSRF protection. We can do this simply by specifying the keys in the persister. This will then filter the data which has been passed and only return the data where that key exists. If you have specified a key in the keys array, however it is not found in the data being passed to the persister then it will set the value of that key to `null`. Here is an example of filtering the data:

```php

use GeorgeHanson\LaravelPersisters\BasePersister;

class MyPersister extends BasePersister
{
    /**
     * The data to filter
     * 
     * @type array
     */
    public $keys = [
        "first_name",
        "last_name"
    ];

    /**
     * Create a new Model
     *
     * @param array $data
     * @return Model
     */
    protected function create(array $data)
    {
        // No matter what is passed to us, $data will only contain "first_name" and "last_name"
    }

    /**
     * Update the given Model
     *
     * @param array $data
     * @param Model $model
     * @return Model
     */
    protected function update(array $data, Model $model)
    {
        // Update the given model here
    }
}
```

Alternatively, if you don't specify any keys it will return all of the data as an array.
