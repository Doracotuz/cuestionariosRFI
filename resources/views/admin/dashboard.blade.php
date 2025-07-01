<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Título de la página --}}
            <div class="bg-white shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ __('Dashboard del Administrador') }}
                    </h2>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-2xl font-bold text-gray-800 mb-6">{{ __('Resumen General') }}</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    {{-- Tarjeta: Total de Usuarios --}}
                    <div class="bg-blue-100 p-4 rounded-lg shadow flex items-center justify-between">
                        <div>
                            <p class="text-sm text-blue-700 font-medium">{{ __('Total de Usuarios') }}</p>
                            <p class="text-3xl font-bold text-blue-900">{{ $totalUsers }}</p>
                        </div>
                        <i class="fas fa-users text-blue-500 text-4xl"></i>
                    </div>

                    {{-- Tarjeta: Cuestionarios Publicados --}}
                    <div class="bg-green-100 p-4 rounded-lg shadow flex items-center justify-between">
                        <div>
                            <p class="text-sm text-green-700 font-medium">{{ __('Cuestionarios Publicados') }}</p>
                            <p class="text-3xl font-bold text-green-900">{{ $publishedQuestionnaires }}</p>
                        </div>
                        <i class="fas fa-file-alt text-green-500 text-4xl"></i>
                    </div>

                    {{-- Tarjeta: Asignaciones Completadas --}}
                    <div class="bg-purple-100 p-4 rounded-lg shadow flex items-center justify-between">
                        <div>
                            <p class="text-sm text-purple-700 font-medium">{{ __('Asignaciones Completadas') }}</p>
                            <p class="text-3xl font-bold text-purple-900">{{ $completedAssignments }}</p>
                        </div>
                        <i class="fas fa-check-circle text-purple-500 text-4xl"></i>
                    </div>

                    {{-- Tarjeta: Respuestas Enviadas --}}
                    <div class="bg-yellow-100 p-4 rounded-lg shadow flex items-center justify-between">
                        <div>
                            <p class="text-sm text-yellow-700 font-medium">{{ __('Respuestas Enviadas') }}</p>
                            <p class="text-3xl font-bold text-yellow-900">{{ $totalSubmittedResponses }}</p>
                        </div>
                        <i class="fas fa-paper-plane text-yellow-500 text-4xl"></i>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {{-- Gráfica de Estado de Cuestionarios --}}
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Estado de Cuestionarios') }}</h4>
                        <div id="questionnaire-status-chart"></div>
                    </div>

                    {{-- Gráfica de Asignaciones --}}
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Estado de Asignaciones') }}</h4>
                        <div id="assignment-status-chart"></div>
                    </div>
                </div>

                <div class="mt-8">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Detalle de Usuarios') }}</h4>
                    <p class="text-gray-700">Administradores: <span class="font-bold">{{ $adminUsers }}</span></p>
                    <p class="text-gray-700">Usuarios Regulares: <span class="font-bold">{{ $regularUsers }}</span></p>
                </div>

                <div class="mt-8">
                    <p class="text-gray-700">
                        Aquí puedes gestionar usuarios, cuestionarios, etc.
                        <a href="{{ route('admin.users.index') }}" class="text-indigo-600 hover:text-indigo-900 font-medium">{{ __('Gestionar Usuarios') }}</a>,
                        <a href="{{ route('admin.questionnaires.index') }}" class="text-indigo-600 hover:text-indigo-900 font-medium">{{ __('Gestionar Cuestionarios') }}</a>,
                        <a href="{{ route('admin.assignments.index') }}" class="text-indigo-600 hover:text-indigo-900 font-medium">{{ __('Gestionar Asignaciones') }}</a>,
                        <a href="{{ route('admin.responses.index') }}" class="text-indigo-600 hover:text-indigo-900 font-medium">{{ __('Ver Respuestas') }}</a>.
                    </p>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Datos para la gráfica de estado de cuestionarios
            var questionnaireStatusOptions = {
                chart: {
                    type: 'donut',
                    height: 300
                },
                labels: ['Borrador', 'Publicado', 'Archivado'],
                series: [{{ $draftQuestionnaires }}, {{ $publishedQuestionnaires }}, {{ $archivedQuestionnaires }}],
                colors: ['#FFC107', '#28A745', '#6C757D'], // Amarillo, Verde, Gris
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 200
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            };
            var questionnaireStatusChart = new ApexCharts(document.querySelector("#questionnaire-status-chart"), questionnaireStatusOptions);
            questionnaireStatusChart.render();

            // Datos para la gráfica de estado de asignaciones
            var assignmentStatusOptions = {
                chart: {
                    type: 'donut',
                    height: 300
                },
                labels: ['Asignado', 'Completado'],
                series: [{{ $assignedAssignments }}, {{ $completedAssignments }}],
                colors: ['#007BFF', '#28A745'], // Azul, Verde
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 200
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            };
            var assignmentStatusChart = new ApexCharts(document.querySelector("#assignment-status-chart"), assignmentStatusOptions);
            assignmentStatusChart.render();
        });
    </script>
    @endpush
</x-app-layout>