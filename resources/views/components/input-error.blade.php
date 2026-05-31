@props(['for'])

@error($for)
    <p {{ $attributes->merge(['class' => 'text-sm text-accent']) }}>{{ $message }}</p>
@enderror
