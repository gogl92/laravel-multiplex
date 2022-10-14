<?php

return [
    /**
     * Model to use for Meta.
     */
    'model' => Kolossal\Meta\Meta::class,

    /**
     * Determine wethere packages migrations should be loaded automatically.
     * Disable this if you want to create your own migrations based on the ones
     * located in `database/migrations`.
     */
    'migrations' => true,

    /**
     * List of handlers for recognized data types.
     *
     * Handlers will be evaluated in order, so a value will be handled
     * by the first appropriate handler in the list.
     *
     * @copyright Plank Multimedia Inc.
     *
     * @link https://github.com/plank/laravel-metable
     */
    'datatypes' => [
        Kolossal\Meta\DataType\BooleanHandler::class,
        Kolossal\Meta\DataType\NullHandler::class,
        Kolossal\Meta\DataType\IntegerHandler::class,
        Kolossal\Meta\DataType\FloatHandler::class,
        Kolossal\Meta\DataType\StringHandler::class,
        Kolossal\Meta\DataType\DateTimeHandler::class,
        Kolossal\Meta\DataType\ArrayHandler::class,
        Kolossal\Meta\DataType\ModelHandler::class,
        Kolossal\Meta\DataType\ModelCollectionHandler::class,
        Kolossal\Meta\DataType\SerializableHandler::class,
        Kolossal\Meta\DataType\ObjectHandler::class,
    ],
];