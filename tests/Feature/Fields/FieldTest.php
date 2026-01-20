<?php

use Wotz\LivewireForms\Fields\Field;

it('can generate an ID', function () {
    $field = new Field('name');

    expect($field->getId())
        ->toBeTruthy()
        ->toBeString()
        ->toStartWith('name-');
});

it('can generate unique ID for multiple fields', function () {
    $field1 = new Field('name');
    $field2 = new Field('name');

    expect($field1->getId())->not->toEqual($field2->getId());
});

it('returns same ID if getId() is called multiple times on the same field', function () {
    $field = new Field('name');

    expect($field->getId())->toEqual($field->getId());
});
