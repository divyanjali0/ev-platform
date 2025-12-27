<aside class="fixed top-14 left-0 w-60 h-[calc(100vh-3.5rem)] bg-gray-900 text-gray-100 flex flex-col">
    <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
        <a href="{{ route('dashboard') }}"
           class="block px-4 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('dashboard') ? 'bg-gray-700' : '' }}">
            Dashboard
        </a>

        <a href="{{ route('itinerary-customers.index') }}" class="block px-4 py-2 rounded hover:bg-gray-700">
            Tour Requests
        </a>
    </nav>
</aside>
