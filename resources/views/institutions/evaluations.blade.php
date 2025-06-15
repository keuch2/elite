<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Reportes de') }}: {{ $institution->name }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('institutions.show', $institution->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    {{ __('Ver Institución') }}
                </a>
                <a href="{{ route('institutions.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                    {{ __('Volver a Instituciones') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="text-lg font-medium">Todos los Reportes</h3>
                        
                        <div class="flex space-x-2">
                            <form action="{{ route('institutions.evaluations', $institution->id) }}" method="GET" class="flex items-center space-x-2">
                                <select name="sport" class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                    <option value="">Todos los Deportes</option>
                                    @foreach($evaluations->pluck('sport')->unique()->filter()->sort() as $sport)
                                        <option value="{{ $sport }}" @selected(request('sport') == $sport)>{{ $sport }}</option>
                                    @endforeach
                                </select>
                                
                                <button type="submit" class="px-3 py-1 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                                    {{ __('Filtrar') }}
                                </button>
                                
                                @if(request()->has('sport'))
                                    <a href="{{ route('institutions.evaluations', $institution->id) }}" class="px-3 py-1 bg-red-600 text-white rounded-md hover:bg-red-700">
                                        {{ __('Limpiar') }}
                                    </a>
                                @endif
                            </form>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Atleta</th>
                                    <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Fecha de Reporte</th>
                                    <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Edad</th>
                                    <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Deporte</th>
                                    <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Categoría</th>
                                    <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Altura</th>
                                    <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Peso</th>
                                    <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($evaluations as $evaluation)
                                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">
                                            <a href="{{ route('athletes.show', $evaluation->id) }}" class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-600 rounded hover:bg-blue-200 dark:bg-blue-900 dark:text-blue-300 dark:hover:bg-blue-800">
                                                {{ $evaluation->first_name }} {{ $evaluation->last_name }}
                                            </a>
                                        </td>
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">
                                            {{ date('d/m/Y', strtotime($evaluation->evaluation_date)) }}
                                        </td>
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">
                                            {{ $evaluation->age }}
                                        </td>
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">
                                            {{ $evaluation->sport ?? '-' }}
                                        </td>
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">
                                            {{ $evaluation->category ?? '-' }}
                                        </td>
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">
                                            {{ $evaluation->anthropometricData->standing_height ?? '-' }} cm
                                        </td>
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">
                                            {{ $evaluation->anthropometricData->weight ?? '-' }} kg
                                        </td>
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">
                                            <a href="{{ route('athletes.evaluation', $evaluation->id) }}" class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-600 rounded hover:bg-blue-200 dark:bg-blue-900 dark:text-blue-300 dark:hover:bg-blue-800">Ver Detalles</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="py-4 px-4 text-center text-gray-500 dark:text-gray-400">
                                            No se encontraron reportes.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <div class="mt-4">
                            {{ $evaluations->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>