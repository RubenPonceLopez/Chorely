{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Chorely')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gradient-to-br from-emerald-50 via-teal-50 to-cyan-50 flex items-center justify-center p-4">

    <div class="w-full max-w-6xl flex flex-col lg:flex-row bg-white rounded-3xl shadow-2xl overflow-hidden">

        {{-- Panel Izquierdo - Branding --}}
        <div class="lg:w-1/2 bg-gradient-to-br from-emerald-500 to-teal-600 p-12 flex flex-col justify-between text-white">
            <div>
                <div class="flex items-center gap-3 mb-8">
                    <div class="bg-white/20 backdrop-blur-sm p-3 rounded-2xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l9-9 9 9M4 10v10h16V10" />
                        </svg>
                    </div>
                    <h1 class="text-4xl font-bold tracking-tight">Chorely</h1>
                </div>

                <h2 class="text-3xl font-semibold mb-4 leading-tight">
                    Organiza tu hogar compartido sin complicaciones
                </h2>

                <p class="text-emerald-50 text-lg mb-12 leading-relaxed">
                    Gestiona las tareas del hogar con tu equipo de forma simple y eficiente.
                    Calendarios compartidos, turnos automáticos y colaboración en tiempo real.
                </p>

                {{-- Características --}}
                <div class="space-y-6">
                    <div class="flex items-start gap-4">
                        <div class="bg-white/20 backdrop-blur-sm p-2.5 rounded-xl flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7H3v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg mb-1">Calendario Inteligente</h3>
                            <p class="text-emerald-50 text-sm">Organiza tareas por días y semanas de forma visual</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="bg-white/20 backdrop-blur-sm p-2.5 rounded-xl flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-emerald-50">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg mb-1">Gestión de Compañeros</h3>
                            <p class="text-emerald-50 text-sm">Asigna tareas equitativamente entre todos</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="bg-white/20 backdrop-blur-sm p-2.5 rounded-xl flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg mb-1">Seguimiento Simple</h3>
                            <p class="text-emerald-50 text-sm">Marca tareas completadas y mantén todo bajo control</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-emerald-50 text-sm mt-12">
                © {{ date('Y') }} Chorely. Convivencia organizada.
            </div>
        </div>

        {{-- Panel Derecho - Contenido dinámico (login, register, etc.) --}}
        <div class="lg:w-1/2 p-12 flex flex-col justify-center">
            <div class="max-w-md mx-auto w-full">

                {{-- Top bar dentro del panel derecho (usuario + logout + admin link)
                     Sólo se muestra si la vista NO ha declarado @section('hide_topbar') --}}
                @unless(View::hasSection('hide_topbar'))
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-4">
                        <a href="{{ url('/') }}" class="text-emerald-600 font-medium">Chorely</a>
                    </div>

                    <div class="flex items-center gap-4">
                        @auth
                            <span class="text-sm text-gray-600">Hola, <strong>{{ auth()->user()->name }}</strong></span>

                            @if(auth()->user()->is_admin ?? false)
                                <a href="{{ url('/admin') }}"
                                   class="px-3 py-2 rounded bg-amber-50 text-amber-700 border border-amber-200 text-sm">
                                    Admin
                                </a>
                            @endif

                            <a href="{{ route('logout') }}" class="px-3 py-2 rounded bg-red-500 text-white text-sm">Cerrar Sesión</a>
                        @endauth

                        @guest
                            <a href="{{ route('login') }}" class="px-3 py-2 rounded bg-emerald-600 text-white text-sm">Iniciar Sesión</a>
                        @endguest
                    </div>
                </div>
                @endunless

                @yield('content')
            </div>
        </div>
    </div>

    {{-- Hidden logout form (POST con CSRF) --}}
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
        @csrf
    </form>

    {{-- Small JS helper: any <a href="/logout"> se convertirá en submit POST --}}
    <script>
      (function(){
        document.addEventListener('click', function(e){
          const a = e.target.closest && e.target.closest('a');
          if (!a) return;
          const href = a.getAttribute('href') || '';
          if (href.replace(window.location.origin, '').split('?')[0].endsWith('/logout')) {
            e.preventDefault();
            const form = document.getElementById('logout-form');
            if (form) form.submit();
          }
        }, true);
      })();
    </script>

    {{-- =========================
         BANNER DE COOKIES (Tailwind - localStorage)
         ========================= --}}
    <div id="chorely-cookie-banner" class="fixed left-4 right-4 bottom-4 max-w-6xl mx-auto bg-white border border-emerald-100 rounded-2xl shadow-2xl p-6 z-[99999] hidden"
         role="dialog" aria-labelledby="chorely-cookie-title" aria-describedby="chorely-cookie-desc" aria-modal="true">
      <div class="flex flex-col md:flex-row md:items-start md:gap-6">
        <div class="md:flex-1">
          <h3 id="chorely-cookie-title" class="text-lg font-semibold text-gray-800">Esta página web usa cookies</h3>
          <p id="chorely-cookie-desc" class="mt-2 text-sm text-gray-600">
            Las cookies se usan para personalizar contenido y anuncios, ofrecer funciones de redes sociales y analizar el tráfico. Compartimos información con partners para publicidad y análisis solo si das tu consentimiento.
          </p>
        </div>

        <div class="mt-4 md:mt-0 md:flex md:flex-col md:justify-between md:items-end">
          <div class="flex gap-3 items-center">
            <button id="chorely-allow-select" class="px-4 py-2 rounded-md font-semibold bg-emerald-600 text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-300">
              Permitir la selección
            </button>
            <button id="chorely-allow-all" class="px-4 py-2 rounded-md font-semibold bg-emerald-800 text-white hover:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-300">
              Permitir todas las cookies
            </button>
          </div>

          <div class="mt-3 w-full md:w-auto">
            <div class="flex items-center gap-3 text-sm text-gray-700">
              <label class="flex items-center gap-2">
                <input id="c_needed" type="checkbox" checked disabled class="w-4 h-4">
                <span class="font-medium">Necesario</span>
              </label>

              <label class="flex items-center gap-2">
                <input id="c_preferences" type="checkbox" class="w-4 h-4">
                <span>Preferencias</span>
              </label>

              <label class="flex items-center gap-2">
                <input id="c_stats" type="checkbox" class="w-4 h-4">
                <span>Estadística</span>
              </label>

              <label class="flex items-center gap-2">
                <input id="c_marketing" type="checkbox" class="w-4 h-4">
                <span>Marketing</span>
              </label>
            </div>

            <div class="mt-2">
              <button id="chorely-show-details" class="text-sm text-gray-500 hover:underline">Mostrar detalles ▾</button>
            </div>
          </div>
        </div>
      </div>

      <div id="chorely-details" class="mt-4 bg-emerald-50 border border-emerald-100 p-4 rounded-md text-sm text-gray-700 hidden" aria-hidden="true">
        <p><strong>Necesario:</strong> Cookies imprescindibles para el funcionamiento.</p>
        <p class="mt-2"><strong>Preferencias:</strong> Guardan idioma y ajustes de la UI.</p>
        <p class="mt-2"><strong>Estadística:</strong> Recogen uso para mejorar la app.</p>
        <p class="mt-2"><strong>Marketing:</strong> Publicidad y anuncios personalizados.</p>
      </div>
    </div>

    {{-- Script de consentimiento (localStorage + detección borrado) --}}
    <script>
    (function(){
      const LS_KEY = 'chorely_cookie_consent_v1';
      const banner = document.getElementById('chorely-cookie-banner');
      const btnAll = document.getElementById('chorely-allow-all');
      const btnSelect = document.getElementById('chorely-allow-select');
      const showDetails = document.getElementById('chorely-show-details');
      const details = document.getElementById('chorely-details');

      const cbPreferences = document.getElementById('c_preferences');
      const cbStats = document.getElementById('c_stats');
      const cbMarketing = document.getElementById('c_marketing');

      // parse / stringify safe
      function getConsent(){
        try {
          const raw = localStorage.getItem(LS_KEY);
          return raw ? JSON.parse(raw) : null;
        } catch(e) { return null; }
      }
      function saveConsent(obj){
        obj.timestamp = new Date().toISOString();
        localStorage.setItem(LS_KEY, JSON.stringify(obj));
      }
      function removeConsent(){
        localStorage.removeItem(LS_KEY);
      }

      // carga condicional de scripts (ajusta las rutas o llamadas a init)
      function loadScriptOnce(src, id){
        if(!src) return;
        if(document.getElementById(id)) return;
        const s = document.createElement('script');
        s.src = src;
        s.id = id;
        s.async = true;
        document.head.appendChild(s);
      }
      function applyConsent(consent){
        if(!consent) return;
        // ejemplo: carga condicional (pon tus rutas o init functions)
        if(consent.statistics){
          loadScriptOnce('https://example.com/analytics.js', 'chorely-analytics');
        }
        if(consent.marketing){
          loadScriptOnce('https://example.com/marketing.js', 'chorely-marketing');
        }
      }

      // Mostrar banner si no hay consentimiento
      function showBanner(){
        banner.classList.remove('hidden');
        // Restaurar checkboxes al estado guardado si existe
        const saved = getConsent();
        if(saved){
          cbPreferences.checked = !!saved.preferences;
          cbStats.checked = !!saved.statistics;
          cbMarketing.checked = !!saved.marketing;
        }
      }
      function hideBanner(){
        banner.classList.add('hidden');
      }

      // Inicial
      const saved = getConsent();
      if(!saved){
        showBanner();
      } else {
        applyConsent(saved);
      }

      // Eventos de botones
      btnAll.addEventListener('click', function(){
        const consent = { necessary: true, preferences: true, statistics: true, marketing: true };
        saveConsent(consent);
        applyConsent(consent);
        hideBanner();
      });

      btnSelect.addEventListener('click', function(){
        const consent = {
          necessary: true,
          preferences: !!cbPreferences.checked,
          statistics: !!cbStats.checked,
          marketing: !!cbMarketing.checked
        };
        saveConsent(consent);
        applyConsent(consent);
        hideBanner();
      });

      showDetails.addEventListener('click', function(){
        const isHidden = details.classList.contains('hidden');
        details.classList.toggle('hidden', !isHidden);
        details.setAttribute('aria-hidden', String(!isHidden));
        showDetails.textContent = isHidden ? 'Ocultar detalles ▴' : 'Mostrar detalles ▾';
      });

      // Detectar borrado o cambios de consentimiento desde OTRA pestaña
      window.addEventListener('storage', function(e){
        if(e.key === LS_KEY){
          // si se elimina (newValue === null) o cambia, mostramos banner si no hay valor
          if(!e.newValue){
            // consentimiento borrado -> mostrar popup/banner
            showBanner();
            // opcional: mostrar una notificación flotante (aquí reusamos el banner)
          } else {
            // nuevo consentimiento guardado en otra pestaña: aplicar y ocultar banner
            try {
              const newConsent = JSON.parse(e.newValue);
              applyConsent(newConsent);
              hideBanner();
            } catch(err){ /* ignore */ }
          }
        }
      });

      // Detectar borrado en la MISMA pestaña (ej. alguien borra desde consola)
      // 1) Al volver a la pestaña (focus), comprobamos
      window.addEventListener('focus', function(){
        const now = getConsent();
        if(!now){
          showBanner();
        }
      });
      // 2) Fallback: comprobación ligera cada 3s (bajo impacto)
      let lastHas = !!getConsent();
      setInterval(function(){
        const curHas = !!getConsent();
        if(lastHas && !curHas){
          // se ha borrado
          showBanner();
        }
        lastHas = curHas;
      }, 3000);

      // API pública
      window.ChorelyCookie = {
        getConsent: getConsent,
        revokeConsent: function(){
          removeConsent();
          showBanner();
        }
      };
    })();
    </script>

</body>

</html>
