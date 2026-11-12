{{-- The help text next to a field: set with gdpr(), or gdprNotice() as form-architect does --}}
@php($gdpr = $field->gdpr ?? $field->gdprNotice)

@if (filled((string) $gdpr))
    <button aria-label="GDPR info label" type="button" class="gdpr-field">
        <span class="gdpr-icon">
            {!! $field->gdprIcon ?? '?' !!}
        </span>

        <span class="gdpr-info">
            {{ $gdpr }}
        </span>
    </button>
@endif
