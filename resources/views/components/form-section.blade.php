@props(['title', 'description' => null])

<div {{ $attributes->merge(['class' => 'mb-6']) }}>
    <div class="mb-4">
        <h4 class="text-md font-semibold text-gray-700 border-b border-gray-200 pb-2">{{ $title }}</h4>
        @if($description)
            <p class="mt-1 text-sm text-gray-500">{{ $description }}</p>
        @endif
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{ $slot }}
    </div>
</div>
