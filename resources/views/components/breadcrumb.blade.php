<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Home</a></li>
        @if(request()->route())
            @php
                $segments = request()->segments();
                $path = '';
            @endphp
            @foreach($segments as $segment)
                @php
                    $path .= '/' . $segment;
                    $label = ucwords(str_replace(['-', '_'], ' ', $segment));
                @endphp
                @if(!$loop->last)
                    <li class="breadcrumb-item"><a href="{{ url($path) }}" class="text-decoration-none">{{ $label }}</a></li>
                @else
                    <li class="breadcrumb-item active" aria-current="page">{{ $label }}</li>
                @endif
            @endforeach
        @endif
    </ol>
</nav>
