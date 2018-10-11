<?php

declare(strict_types=1);

namespace Overblog\GraphQLBundle\Tests\Functional\MultipleSchema;

use Overblog\GraphQLBundle\Tests\Functional\TestCase;

class MultipleSchemaTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        static::bootKernel(['test_case' => 'multipleSchema']);
    }

    public function testPublicSchema(): void
    {
        $result = $this->executeGraphQLRequest('{foo}', [], 'public');
        $this->assertSame('foo', $result['data']['foo']);
        $this->assertSchemaQueryTypeName('PublicQuery');

        $result = $this->executeGraphQLRequest('{users{edges{node{username}}}}', [], 'public');
        $this->assertSame([['node' => ['username' => 'user1']]], $result['data']['users']['edges']);
    }

    public function testInternalSchema(): void
    {
        $result = $this->executeGraphQLRequest('{bar foo}', [], 'internal');
        $this->assertSame('bar', $result['data']['bar']);
        $this->assertSame('foo', $result['data']['foo']);
        $this->assertSchemaQueryTypeName('InternalQuery');

        $result = $this->executeGraphQLRequest('{users{edges{node{username email}}}}', [], 'internal');
        $this->assertSame([['node' => ['username' => 'user1', 'email' => 'topsecret']]], $result['data']['users']['edges']);
    }

    private function assertSchemaQueryTypeName($typeName): void
    {
        $query = $this->getContainer()->get('overblog_graphql.type_resolver')->resolve($typeName);
        $this->assertSame('Query', $query->name);
    }
}
