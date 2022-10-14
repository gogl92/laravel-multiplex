<?php

namespace Kolossal\Meta\Tests;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Kolossal\Meta\Meta;
use Kolossal\Meta\Tests\Mocks\Post;
use Kolossal\Meta\Tests\Mocks\SampleSerializable;
use stdClass;

class MetaTest extends TestCase
{
    use RefreshDatabase;

    public function handlerProvider()
    {
        $timestamp = '2017-01-01 00:00:00.000000+0000';
        $datetime = Carbon::createFromFormat('Y-m-d H:i:s.uO', $timestamp);

        $object = new stdClass();
        $object->foo = 'bar';
        $object->baz = 3;

        return [
            'array' => [
                'array',
                ['foo' => ['bar'], 'baz'],
            ],
            'boolean' => [
                'boolean',
                true,
            ],
            'datetime' => [
                'datetime',
                $datetime,
            ],
            'float' => [
                'float',
                1.1,
            ],
            'integer' => [
                'integer',
                3,
            ],
            'model' => [
                'model',
                new Post,
            ],
            'model collection' => [
                'collection',
                new Collection([new Post]),
            ],
            'null' => [
                'null',
                null,
            ],
            'object' => [
                'object',
                $object,
            ],
            'serializable' => [
                'serializable',
                new SampleSerializable(['foo' => 'bar']),
            ],
            'string' => [
                'string',
                'foo',
            ],
        ];
    }

    /** @test */
    public function it_can_get_and_set_value()
    {
        $meta = Meta::factory()->make();

        $meta->value = 'foo';

        $this->assertEquals('foo', $meta->value);
        $this->assertEquals('string', $meta->type);
    }

    /** @test */
    public function it_exposes_its_serialized_value()
    {
        $meta = Meta::factory()->make();
        $meta->value = 123;

        $this->assertEquals('123', $meta->rawValue);
        $this->assertEquals('123', $meta->raw_value);
    }

    /** @test */
    public function it_caches_unserialized_value()
    {
        $meta = Meta::factory()->make();
        $meta->value = 'foo';

        $this->assertEquals('foo', $meta->value);

        $meta->setRawAttributes(['value' => 'bar'], true);

        $this->assertEquals('foo', $meta->value);
        $this->assertEquals('bar', $meta->rawValue);
        $this->assertEquals('bar', $meta->raw_value);
    }

    /** @test */
    public function it_clears_cache_on_set()
    {
        $meta = Meta::factory()->make();

        $meta->value = 'foo';

        $this->assertEquals('foo', $meta->value);

        $meta->value = 'bar';

        $this->assertEquals('bar', $meta->value);
    }

    public function test_it_can_get_its_model_relation()
    {
        $meta = Meta::factory()->make();

        $relation = $meta->metable();

        $this->assertInstanceOf(MorphTo::class, $relation);
        $this->assertEquals('metable_type', $relation->getMorphType());
        $this->assertEquals('metable_id', $relation->getForeignKeyName());
    }

    /**
     * @test
     * @dataProvider handlerProvider
     */
    public function it_can_store_and_retrieve_datatypes($type, $input)
    {
        $this->useDatabase();

        $meta = Meta::factory()->make([
            'metable_type' => 'Foo\Bar\Model',
            'metable_id' => 1,
            'key' => 'dummy',
        ]);

        $meta->value = $input;
        $meta->save();

        $meta->refresh();

        $this->assertEquals($type, $meta->type);
        $this->assertEquals($input, $meta->value);
        $this->assertIsString($meta->raw_value);
    }
}