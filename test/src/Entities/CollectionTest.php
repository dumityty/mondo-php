<?php

namespace Edcs\Mondo\Test\Entities;

use Edcs\Mondo\Entitites\Collection;
use Edcs\Mondo\Entitites\Entity;
use Edcs\Mondo\Test\Stubs\EntityStub;
use Edcs\Mondo\Test\TestCase;
use GuzzleHttp\Psr7\Response;

class CollectionTest extends TestCase
{
    /**
     * Creates a fake json transaction response.
     *
     * @return array
     */
    private function createResponse()
    {
        return [
            'data' => [
                ['foo' => uniqid()],
                ['foo' => uniqid()],
                ['foo' => uniqid()]
            ]
        ];
    }

    /**
     * Ensures that the offset exists method is setup correctly.
     */
    public function testOffsetExists()
    {
        $json = $this->createResponse();

        $response = new Response(200, [], json_encode($json));

        $collection = new Collection($response, 'data', EntityStub::class);

        $this->assertTrue($collection->offsetExists(0));
        $this->assertFalse($collection->offsetExists(10));
    }

    /**
     * Ensures that the offset get method is setup correctly.
     */
    public function testOffsetGet()
    {
        $json = $this->createResponse();

        $response = new Response(200, [], json_encode($json));

        $collection = new Collection($response, 'data', EntityStub::class);

        $this->assertEquals($json['data'][0]['foo'], $collection[0]['foo']);
    }

    /**
     * Ensures that the offset set method is setup correctly.
     */
    public function testOffsetSet()
    {
        $json = $this->createResponse();

        $response = new Response(200, [], json_encode($json));

        $collection = new Collection($response, 'data', EntityStub::class);

        $this->assertEquals($json['data'][0]['foo'], $collection[0]['foo']);

        $collection[0]['foo'] = $unique = uniqid();
        $collection[] = ['foo' => $unique];

        $this->assertEquals($unique, $collection[0]['foo']);
        $this->assertEquals($unique, $collection[3]['foo']);
    }

    /**
     * Ensures that the offset unset method is setup correctly.
     */
    public function testOffsetUnSet()
    {
        $json = $this->createResponse();

        $response = new Response(200, [], json_encode($json));

        $collection = new Collection($response, 'data', EntityStub::class);

        $this->assertEquals($json['data'][0]['foo'], $collection[0]['foo']);

        $collection->offsetUnset(2);

        $this->assertFalse($collection->offsetExists(2));
    }

    /**
     * Ensures that a collection can be iterated and returns an entity instance.
     */
    public function testCollectionCanBeInterated()
    {
        $json = $this->createResponse();

        $response = new Response(200, [], json_encode($json));

        $collection = new Collection($response, 'data', EntityStub::class);

        foreach ($collection as $entity) {
            $this->assertInstanceOf(Entity::class, $entity);
        }
    }
}
