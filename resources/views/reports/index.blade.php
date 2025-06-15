<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Reportes') }}
            </h2>
            <div class="flex space-x-4">
                <a href="{{ url('/reports/create') }}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                    {{ __('Crear Reporte') }}
                </a>
                <a href="{{ url('/import/form') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    {{ __('Importar Datos') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">Reportes Generados</h3>

                    <!-- Filter Form -->
                    <div class="mb-6 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <form action="{{ url('/reports') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label for="athlete" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Atleta</label>
                                <input type="text" name="athlete" id="athlete" value="{{ request('athlete') }}" 
                                    class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                    placeholder="Nombre del atleta">
                            </div>
                            
                            <div>
                                <label for="institution_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Institución</label>
                                <select name="institution_id" id="institution_id" 
                                    class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Todas las instituciones</option>
                                    @foreach($institutions as $institution)
                                        <option value="{{ $institution->id }}" {{ request('institution_id') == $institution->id ? 'selected' : '' }}>
                                            {{ $institution->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label for="template_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Plantilla</label>
                                <select name="template_id" id="template_id" 
                                    class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Todas las plantillas</option>
                                    @foreach($templates as $template)
                                        <option value="{{ $template->id }}" {{ request('template_id') == $template->id ? 'selected' : '' }}>
                                            {{ $template->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label for="sort" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ordenar por</label>
                                <div class="flex space-x-2">
                                    <select name="sort" id="sort" 
                                        class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="created_at" {{ request('sort', 'created_at') == 'created_at' ? 'selected' : '' }}>Fecha</option>
                                        <option value="id" {{ request('sort') == 'id' ? 'selected' : '' }}>ID</option>
                                    </select>
                                    <select name="direction" id="direction" 
                                        class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="desc" {{ request('direction', 'desc') == 'desc' ? 'selected' : '' }}>Descendente</option>
                                        <option value="asc" {{ request('direction') == 'asc' ? 'selected' : '' }}>Ascendente</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="md:col-span-4 flex justify-end">
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                    Filtrar
                                </button>
                                <a href="{{ url('/reports') }}" class="ml-2 px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                                    Limpiar
                                </a>
                            </div>
                        </form>
                    </div>

                    @if($reports->isEmpty())
                        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                            <p>No se han generado reportes todavía.</p>
                            <p class="mt-2">Puede crear un reporte manualmente o importar datos para generar reportes.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700">
                                <thead>
                                    <tr>
                                        <th class="py-3 px-4 border-b border-gray-300 dark:border-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            ID
                                        </th>
                                        <th class="py-3 px-4 border-b border-gray-300 dark:border-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Atleta
                                        </th>
                                        <th class="py-3 px-4 border-b border-gray-300 dark:border-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Plantilla
                                        </th>
                                        <th class="py-3 px-4 border-b border-gray-300 dark:border-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Fecha de Creación
                                        </th>
                                        <th class="py-3 px-4 border-b border-gray-300 dark:border-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Acciones
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reports as $report)
                                        <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                            <td class="py-3 px-4 border-b border-gray-300 dark:border-gray-700 text-sm">
                                                {{ $report->id }}
                                            </td>
                                            <td class="py-3 px-4 border-b border-gray-300 dark:border-gray-700 text-sm">
                                                @if($report->athlete && $report->athlete->athleteProfile)
                                                    {{ $report->athlete->athleteProfile->first_name ?? '' }} 
                                                    {{ $report->athlete->athleteProfile->last_name ?? '' }}
                                                @else
                                                    <span class="text-red-500">Atleta no encontrado</span>
                                                @endif
                                            </td>
                                            <td class="py-3 px-4 border-b border-gray-300 dark:border-gray-700 text-sm">
                                                @if($report->template)
                                                    {{ $report->template->name }}
                                                @else
                                                    <span class="text-gray-500">Plantilla personalizada</span>
                                                @endif
                                            </td>
                                            <td class="py-3 px-4 border-b border-gray-300 dark:border-gray-700 text-sm">
                                                {{ $report->created_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="py-3 px-4 border-b border-gray-300 dark:border-gray-700 text-sm">
                                                <div class="flex space-x-2">
                                                    <a href="{{ url('/reports/'.$report->id) }}" class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-600 rounded hover:bg-blue-200 dark:bg-blue-900 dark:text-blue-300 dark:hover:bg-blue-800">
                                                        Ver
                                                    </a>
                                                    <a href="{{ url('/reports/'.$report->id.'/edit') }}" class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-600 rounded hover:bg-yellow-200 dark:bg-yellow-900 dark:text-yellow-300 dark:hover:bg-yellow-800">
                                                        Editar
                                                    </a>
                                                    <a href="{{ url('/reports/'.$report->id.'/pdf') }}" class="px-2 py-1 text-xs font-medium bg-green-100 text-green-600 rounded hover:bg-green-200 dark:bg-green-900 dark:text-green-300 dark:hover:bg-green-800">
                                                        PDF
                                                    </a>
                                                    <button
                                                        type="button"
                                                        onclick="document.getElementById('delete-report-{{$report->id}}').classList.remove('hidden')"
                                                        class="px-2 py-1 text-xs font-medium bg-red-100 text-red-600 rounded hover:bg-red-200 dark:bg-red-900 dark:text-red-300 dark:hover:bg-red-800">
                                                        Eliminar
                                                    </button>
                                                </div>
                                                
                                                <!-- Modal de confirmación de eliminación -->
                                                <div id="delete-report-{{$report->id}}" class="fixed inset-0 z-50 hidden overflow-y-auto">
                                                    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                                        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                                                            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                                                        </div>
                                                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                                                        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                                            <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                                <div class="sm:flex sm:items-start">
                                                                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                                                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">
                                                                            Confirmar eliminación
                                                                        </h3>
                                                                        <div class="mt-2">
                                                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                                                ¿Está seguro que desea eliminar este reporte? Esta acción no se puede deshacer.
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                                                <form action="{{ route('reports.destroy', $report->id) }}" method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                                                                        Eliminar
                                                                    </button>
                                                                </form>
                                                                <button type="button" onclick="document.getElementById('delete-report-{{$report->id}}').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                                                    Cancelar
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $reports->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
