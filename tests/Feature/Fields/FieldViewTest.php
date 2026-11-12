<?php

use Illuminate\Support\HtmlString;
use Illuminate\Support\ViewErrorBag;
use Wotz\LivewireForms\Fields\TextareaField;
use Wotz\LivewireForms\Fields\TextField;

beforeEach(function () {
    view()->share('errors', new ViewErrorBag);
});

it('uses the placeholder that was set, and the label without one', function () {
    expect(TextField::make('name', 'Name')->placeholder('Your name')->render()->render())
        ->toContain('placeholder="Your name"');

    expect(TextField::make('name', 'Name')->placeholder('')->render()->render())
        ->toContain('placeholder=""');

    expect(TextareaField::make('message', 'Message')->placeholder('')->render()->render())
        ->toContain('placeholder=""');

    expect(TextField::make('name', 'Name')->render()->render())
        ->toContain('placeholder="Name"');
});

it('shows the help text set with gdpr() or gdprNotice()', function () {
    expect(TextField::make('name', 'Name')->gdpr('Only used for this request')->render()->render())
        ->toContain('Only used for this request');

    expect(TextField::make('name', 'Name')->gdprNotice(new HtmlString('Who did the <strong>onboarding</strong>'))->render()->render())
        ->toContain('class="gdpr-field"')
        ->toContain('Who did the <strong>onboarding</strong>');
});

it('shows no help button for an empty help text', function () {
    expect(TextField::make('name', 'Name')->gdprNotice(new HtmlString(''))->render()->render())
        ->not->toContain('gdpr-field');

    expect(TextField::make('name', 'Name')->render()->render())
        ->not->toContain('gdpr-field');
});

it('marks the label of a required field', function () {
    expect(TextField::make('name', 'Name')->required(true)->render()->render())
        ->toMatch('/<label[^>]*class="[^"]*\brequired\b/');

    expect(TextField::make('name', 'Name')->render()->render())
        ->not->toMatch('/<label[^>]*class="[^"]*\brequired\b/');
});
