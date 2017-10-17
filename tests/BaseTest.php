<?php

namespace GeorgeHanson\LaravelPersisters\Tests;

use Illuminate\Support\Collection;
use GeorgeHanson\LaravelPersisters\BasePersister;
use GeorgeHanson\LaravelPersisters\Tests\TestModel;
use GeorgeHanson\LaravelPersisters\Contracts\Persister;

class BaseTest extends TestCase
{
    public function testTheAbstractClassImplementsTheInterface()
    {
        $stub = $this->getMockForAbstractClass(BasePersister::class);
        $this->assertInstanceOf(Persister::class, $stub);
    }

    public function testItCanCreateFromArrayableData()
    {
        $data = new Collection([
            'first_name' => 'John',
            'last_name' => 'Doe'
        ]);

        $stub = $this->getMockForAbstractClass(BasePersister::class);
        $stub->expects($this->once())->method('create')->will($this->returnValue(true));

        $this->assertTrue($stub->persist($data));
    }

    public function testItCanCreateFromAnArrayOfData()
    {
        $data = [
            'first_name' => 'John',
            'last_name' => 'Doe'
        ];

        $stub = $this->getMockForAbstractClass(BasePersister::class);
        $stub->expects($this->once())->method('create')->will($this->returnValue(true));

        $this->assertTrue($stub->persist($data));
    }

    public function testItCanUpdateFromArrayableData()
    {
        $data = new Collection([
            'first_name' => 'John',
            'last_name' => 'Doe'
        ]);

        $stub = $this->getMockForAbstractClass(BasePersister::class);
        $stub->expects($this->once())->method('update')->will($this->returnValue(true));
        $model = new TestModel();
        $this->assertTrue($stub->persist($data, $model));
    }

    public function testItCanUpdateFromAnArrayOfData()
    {
        $data = [
            'first_name' => 'John',
            'last_name' => 'Doe'
        ];

        $stub = $this->getMockForAbstractClass(BasePersister::class);
        $stub->expects($this->once())->method('update')->will($this->returnValue(true));
        $model = new TestModel();

        $this->assertTrue($stub->persist($data, $model));
    }

    public function testIfNoKeysAreSpecifiedItDoesNotFilterTheDataWhenCreatingFromArrayable()
    {
        $data = new Collection([
            'first_name' => 'John',
            'last_name' => 'Doe'
        ]);

        $stub = $this->getMockForAbstractClass(BasePersister::class);
        $stub->expects($this->once())->method('create')->with($this->callback(function ($data) {
            $keys = array_keys($data);
            return (count($keys) === 2) && $keys[0] === 'first_name' && $keys[1] == 'last_name';
        }))->will($this->returnValue(true));

        $this->assertTrue($stub->persist($data));
    }

    public function testIfNoKeysAreSpecifiedItDoesNotFilterTheDataWhenCreatingFromAnArray()
    {
        $data = [
            'first_name' => 'John',
            'last_name' => 'Doe'
        ];

        $stub = $this->getMockForAbstractClass(BasePersister::class);
        $stub->expects($this->once())->method('create')->with($this->callback(function ($data) {
            $keys = array_keys($data);
            return (count($keys) === 2) && $keys[0] === 'first_name' && $keys[1] == 'last_name';
        }))->will($this->returnValue(true));

        $this->assertTrue($stub->persist($data));
    }

    public function testIfNoKeysAreSpecifiedItDoesNotFilterTheDataWhenUpdatingFromArrayable()
    {
        $data = new Collection([
            'first_name' => 'John',
            'last_name' => 'Doe'
        ]);
        $testModel = new TestModel();

        $stub = $this->getMockForAbstractClass(BasePersister::class);
        $stub->expects($this->once())->method('update')->with(
            $this->callback(function ($data) {
                $keys = array_keys($data);
                return (count($keys) === 2) && $keys[0] === 'first_name' && $keys[1] == 'last_name';
            }),
            $this->callback(function ($model) use ($testModel) {
                return $model === $testModel;
            })
        )->will($this->returnValue(true));


        $this->assertTrue($stub->persist($data, $testModel));
    }

    public function testIfNoKeysAreSpecifiedItDoesNotFilterTheDataWhenUpdatingFromAnArray()
    {
        $data = [
            'first_name' => 'John',
            'last_name' => 'Doe'
        ];

        $testModel = new TestModel();

        $stub = $this->getMockForAbstractClass(BasePersister::class);
        $stub->expects($this->once())->method('update')->with(
            $this->callback(function ($data) {
                $keys = array_keys($data);
                return (count($keys) === 2) && $keys[0] === 'first_name' && $keys[1] == 'last_name';
            }),
            $this->callback(function ($model) use ($testModel) {
                return $model === $testModel;
            })
        )->will($this->returnValue(true));


        $this->assertTrue($stub->persist($data, $testModel));
    }

    public function testItFiltersTheDataIfKeysAreSetWhenCreatingFromArrayable()
    {
        $data = new Collection([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com'
        ]);

        $stub = $this->getMockForAbstractClass(BasePersister::class);

        $stub->keys = [
            'first_name',
            'last_name'
        ];

        $stub->expects($this->once())->method('create')->with($this->callback(function ($data) {
            $keys = array_keys($data);
            return (count($keys) === 2) && $keys[0] === 'first_name' && $keys[1] == 'last_name';
        }))->will($this->returnValue(true));

        $this->assertTrue($stub->persist($data));
    }

    public function testItFiltersTheDataIfKeysAreSetWhenCreatingFromArray()
    {
        $data = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com'
        ];

        $stub = $this->getMockForAbstractClass(BasePersister::class);

        $stub->keys = [
            'first_name',
            'last_name'
        ];

        $stub->expects($this->once())->method('create')->with($this->callback(function ($data) {
            $keys = array_keys($data);
            return (count($keys) === 2) && $keys[0] === 'first_name' && $keys[1] == 'last_name';
        }))->will($this->returnValue(true));

        $this->assertTrue($stub->persist($data));
    }

    public function testItFiltersTheDataIfKeysAreSetWhenUpdatingFromArrayable()
    {
        $data = new Collection([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com'
        ]);
        $testModel = new TestModel();

        $stub = $this->getMockForAbstractClass(BasePersister::class);

        $stub->keys = [
            'first_name',
            'last_name'
        ];

        $stub->expects($this->once())->method('update')->with(
            $this->callback(function ($data) {
                $keys = array_keys($data);
                return (count($keys) === 2) && $keys[0] === 'first_name' && $keys[1] == 'last_name';
            }),
            $this->callback(function ($model) use ($testModel) {
                return $model === $testModel;
            })
        )->will($this->returnValue(true));


        $this->assertTrue($stub->persist($data, $testModel));
    }

    public function testItFiltersTheDataIfKeysAreSetWhenUpdatingFromArray()
    {
        $data = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com'
        ];

        $testModel = new TestModel();

        $stub = $this->getMockForAbstractClass(BasePersister::class);

        $stub->keys = [
            'first_name',
            'last_name'
        ];

        $stub->expects($this->once())->method('update')->with(
            $this->callback(function ($data) {
                $keys = array_keys($data);
                return (count($keys) === 2) && $keys[0] === 'first_name' && $keys[1] == 'last_name';
            }),
            $this->callback(function ($model) use ($testModel) {
                return $model === $testModel;
            })
        )->will($this->returnValue(true));


        $this->assertTrue($stub->persist($data, $testModel));
    }
}
