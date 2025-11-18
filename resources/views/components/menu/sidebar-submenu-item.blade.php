@props([
    'href' => '#', // can be named route or path
    'icon' => 'circle-gauge',
    'text' => '',
    'active' => false,
    'activePatterns' => [], // Additional patterns to check for active state
])

@php
    use Illuminate\Support\Facades\Route as RouteFacade;
    use Illuminate\Support\Facades\Request;
    
    $isAbsolute = is_string($href) && (str_starts_with($href, '/') || preg_match('#^https?://#', $href));
    $url = $isAbsolute ? $href : (RouteFacade::has($href) ? route($href) : url($href));
    
    // Auto-detect active state if not explicitly set
    $isActive = $active;
    
    if (!$isActive) {
        $currentRoute = Request::route() ? Request::route()->getName() : null;
        $currentPath = Request::path();
        
        // Check if current route matches the href
        if ($currentRoute && !$isAbsolute) {
            $isActive = $currentRoute === $href;
        }
        
        // Check current path for absolute URLs
        if (!$isActive && $isAbsolute) {
            $isActive = $currentPath === trim($href, '/');
        }
        
        // Check additional patterns
        if (!$isActive && !empty($activePatterns)) {
            foreach ($activePatterns as $pattern) {
                if (str_contains($currentPath, $pattern) || ($currentRoute && str_contains($currentRoute, $pattern))) {
                    $isActive = true;
                    break;
                }
            }
        }
        
        // Fallback: check if current path starts with the href path
        if (!$isActive && !$isAbsolute) {
            $hrefPath = str_replace('.', '/', $href);
            $isActive = str_starts_with($currentPath, $hrefPath);
        }
    }
@endphp

<a href="{{ $url }}" class="side-menu__link {{ $isActive ? 'side-menu__link--active' : '' }}">
    <i data-lucide="{{ $icon }}" class="size-4 stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 side-menu__link__icon"></i>
    <div class="side-menu__link__title">
        {{ $text }}
    </div>
</a>


