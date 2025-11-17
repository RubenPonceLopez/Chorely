{{-- Navbar con botón de cerrar sesión --}}
<nav class="bg-white shadow-md">
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
        <div class="flex items-center gap-8">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-emerald-600 hover:text-emerald-700 font-semibold">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l9-9 9 9M4 10v10h16V10" />
                </svg>
                Chorely
            </a>
        </div>
        <div class="flex items-center gap-4">
            @if(Auth::check())
                <span class="text-sm text-gray-600">Hola, <strong>{{ Auth::user()->name }}</strong></span>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-all duration-200 text-sm font-medium">
                        Cerrar Sesión
                    </button>
                </form>
            @endif
        </div>
    </div>
</nav>

