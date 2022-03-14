<?php

namespace Tests;

trait ResourceStructure
{
    private array $paginationStructure = [
        'meta' => [
            'current_page',
            'from',
            'path',
            'per_page',
            'to',
        ],
        'links' => [
            'first',
            'last',
            'prev',
            'next',
        ],
    ];

    private array $resourceMetaDataStructure = [
        'status',
        'type',
        'title',
    ];
}
