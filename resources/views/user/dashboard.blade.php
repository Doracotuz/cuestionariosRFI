<x-app-layout>
    {{-- El x-slot name="header" se ha eliminado de aquí --}}

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Título de la página movido aquí --}}
            <div class="bg-white shadow-sm sm:rounded-lg mb-6"> {{-- Añadido mb-6 para un margen inferior --}}
                <div class="p-6 text-gray-900">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ __('Dashboard de Usuario') }}
                    </h2>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("¡Hola Usuario! Estás logueado.") }}
                    <p>Aquí puedes ver y responder cuestionarios.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
