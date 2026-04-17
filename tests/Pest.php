<?php

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

uses(TestCase::class)->in('Feature');
uses(LazilyRefreshDatabase::class)->in('Feature');
