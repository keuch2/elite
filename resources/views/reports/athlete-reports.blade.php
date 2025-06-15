<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Reportes de {{ $athlete->first_name }} {{ $athlete->last_name }} - {{ date('d/m/Y', strtotime($athlete->evaluation_date)) }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ url('/athletes/'.$athlete->id.'/evaluation') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                    {{ __('Volver a Evaluación') }}
                </a>
                <a href="{{ url('/reports/create') }}?preselect_athlete={{ $athlete->id }}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                    {{ __('Crear Nuevo Reporte') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 px-4 py-2 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 px-4 py-2 bg-red-100 border border-red-400 text-red-700 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium">Reportes para esta evaluación</h3>
                        
                        @if($reports->count() > 1)
                            <form action="{{ url('/reports/compare') }}" method="POST" class="flex items-center">
                                @csrf
                                <input type="hidden" name="report_ids" id="selected-reports" value="">
                                <button type="submit" id="compare-btn" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed" disabled>
                                    {{ __('Comparar Seleccionados') }}
                                </button>
                            </form>
                        @endif
                    </div>

                    @if($reports->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700">
                                <thead>
                                    <tr>
                                        @if($reports->count() > 1)
                                            <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">
                                                <input type="checkbox" id="select-all" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            </th>
                                        @endif
                                        <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">ID</th>
                                        <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Plantilla</th>
                                        <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Fecha de Creación</th>
                                        <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reports as $report)
                                        <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                            @if($reports->count() > 1)
                                                <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">
                                                    <input type="checkbox" name="report_checkbox" value="{{ $report->id }}" class="report-checkbox rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                </td>
                                            @endif
                                            <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">{{ $report->id }}</td>
                                            <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">{{ $report->template->name }}</td>
                                            <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">{{ date('d/m/Y', strtotime($report->created_at)) }}</td>
                                            <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">
                                                <div class="flex space-x-2">
                                                    <a href="{{ url('/reports/'.$report->id) }}" class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-600 rounded hover:bg-blue-200 dark:bg-blue-900 dark:text-blue-300 dark:hover:bg-blue-800">
                                                        Ver
                                                    </a>
                                                    <a href="{{ url('/reports/'.$report->id.'/export-pdf') }}" class="px-2 py-1 text-xs font-medium bg-green-100 text-green-600 rounded hover:bg-green-200 dark:bg-green-900 dark:text-green-300 dark:hover:bg-green-800">
                                                        PDF
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        No hay reportes disponibles para esta evaluación.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if($reports->count() > 1)
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const selectAll = document.getElementById('select-all');
                const reportCheckboxes = document.querySelectorAll('.report-checkbox');
                const compareBtn = document.getElementById('compare-btn');
                const selectedReportsInput = document.getElementById('selected-reports');
                
                // Function to update the hidden input and button state
                function updateSelectedReports() {
                    const selectedReports = Array.from(reportCheckboxes)
                        .filter(checkbox => checkbox.checked)
                        .map(checkbox => checkbox.value);
                    
                    selectedReportsInput.value = selectedReports.join(',');
                    compareBtn.disabled = selectedReports.length < 2;
                }
                
                // Select all checkbox
                selectAll.addEventListener('change', function() {
                    reportCheckboxes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });
                    updateSelectedReports();
                });
                
                // Individual checkboxes
                reportCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        const allChecked = Array.from(reportCheckboxes).every(cb => cb.checked);
                        const anyChecked = Array.from(reportCheckboxes).some(cb => cb.checked);
                        
                        selectAll.checked = allChecked;
                        selectAll.indeterminate = anyChecked && !allChecked;
                        
                        updateSelectedReports();
                    });
                });
            });
        </script>
    @endif
</x-app-layout>
