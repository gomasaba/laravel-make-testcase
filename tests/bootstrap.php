<?php
$loader = require __DIR__ . '/../vendor/autoload.php';
$loader->addPsr4('DummyApp\\', __DIR__ . '/app');

function app_path()
{
    return __DIR__ . '/app';
}

function app_namespace()
{
    return 'DummyApp\\';
}

function tests_path()
{
    return __DIR__ . '/tests';
}

function fixture_path()
{
    return __DIR__ . '/fixture';
}
