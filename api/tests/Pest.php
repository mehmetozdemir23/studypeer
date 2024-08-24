<?php
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\PermissionTestCase;
use Tests\TestCase;

uses(
    PermissionTestCase::class,
    RefreshDatabase::class
)->in('Feature/Permissions');

uses(
    TestCase::class,
    RefreshDatabase::class
)->in('Feature/AuthTest.php', 'Feature/ExampleTest.php');

