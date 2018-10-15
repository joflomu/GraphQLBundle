<?php

declare(strict_types=1);

namespace Overblog\GraphQLBundle\Annotation;

/**
 * Annotation for GraphQL public on fields.
 *
 * @Annotation
 * @Target({"CLASS", "METHOD", "PROPERTY"})
 */
final class IsPublic
{
    /**
     * Field publicity.
     *
     * @var string
     */
    public $value;
}