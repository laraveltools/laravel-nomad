<?php
namespace ShiftOneLabs\LaravelNomad\Tests;

use ReflectionMethod;
use Laravel\Lumen\Application;
use Illuminate\Database\Schema\Builder;
use ShiftOneLabs\LaravelNomad\Tests\Stubs\PdoStub;
use Laravel\Lumen\Testing\TestCase as BaseTestCase;
use Illuminate\Database\Schema\Blueprint as Blueprint;
use Illuminate\Database\Schema\Grammars\Grammar as Grammar;

class TestCase extends BaseTestCase
{

    public function createApplication()
    {
        $app = new Application();
        $app->withEloquent();
        $app->register(\ShiftOneLabs\LaravelNomad\LaravelNomadServiceProvider::class);

        return $app;
    }

    public function makeConnection($type)
    {
        $pdo = new PdoStub();

        return $this->app->make('db.connection.' . $type, [$pdo, 'database']);
    }

    public function getNewBlueprint($table = 'table')
    {
        return new \ShiftOneLabs\LaravelNomad\Extension\Database\Schema\Blueprint($table);
    }

    public function getBuilderBlueprint(Builder $builder, $table = 'table')
    {
        return $this->callRestrictedMethod($builder, 'createBlueprint', [$table]);
    }

    public function getColumnSql(Grammar $grammer, Blueprint $blueprint)
    {
        return $this->callRestrictedMethod($grammer, 'getColumns', [$blueprint]);
    }

    public function callRestrictedMethod($object, $method, array $args = [])
    {
        $reflectionMethod = new ReflectionMethod($object, $method);
        $reflectionMethod->setAccessible(true);

        return $reflectionMethod->invokeArgs($object, $args);
    }
}