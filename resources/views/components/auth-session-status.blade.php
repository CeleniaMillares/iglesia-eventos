@props(['status'])

@if ($status)
    <div role="status" {{ $attributes->merge(['class' => 'alert alert-success py-2']) }}>
        {{ $status }}
    </div>
@endif
