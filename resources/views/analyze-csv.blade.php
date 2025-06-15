<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Analizar Datos CSV') }}
            </h2>
            <a href="{{ route('import.form') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                {{ __('Volver a Importar') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">Vista Previa del Archivo CSV</h3>
                    <p class="mb-4 text-gray-600 dark:text-gray-400">
                        Revise los datos antes de importar. El sistema validará los datos y destacará cualquier problema potencial.
                    </p>

                    <div class="bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-800 rounded-md p-4 mb-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Modo Vista Previa</h4>
                                <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                                    <p>
                                        Esta es una vista previa de sus datos. Por favor, revise cualquier error o inconsistencia antes de continuar con la importación.
                                        Una vez que confirme, los atletas y sus datos asociados serán creados o actualizados en la base de datos.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700">
                            <thead>
                                <tr>
                                    @foreach($headers as $header)
                                        <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left text-xs">
                                            {{ $header }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rows as $index => $row)
                                    @if($index < 10) {{-- Show only first 10 rows for preview --}}
                                        <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                            @foreach($row as $cellIndex => $cell)
                                                <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-xs">
                                                    {{ $cell }}
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="{{ count($headers) }}" class="py-4 px-4 text-center text-gray-500 dark:text-gray-400">
                                            No se encontraron datos en el archivo CSV.
                                        </td>
                                    </tr>
                                @endforelse
                                @if(count($rows) > 10)
                                    <tr>
                                        <td colspan="{{ count($headers) }}" class="py-2 px-4 text-center text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700">
                                            ... {{ count($rows) - 10 }} filas más no mostradas en la vista previa
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4 text-gray-600 dark:text-gray-400">
                        <p>Total de filas: {{ count($rows) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">Opciones de Importación</h3>

                    <div class="space-y-4">
                        <div>
                            <h4 class="text-md font-medium">¿Qué le gustaría hacer con estos datos?</h4>

                            <div class="mt-4 flex flex-col md:flex-row gap-4">
                                <form action="{{ route('generate.reports') }}" method="POST" class="flex-1">
                                    @csrf
                                    <input type="hidden" name="csv_path" value="{{ $path }}">
                                    
                                    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-300 dark:border-gray-700 p-4 h-full">
                                        <h5 class="font-medium mb-2">Generar Informes</h5>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                            Crear o actualizar atletas y generar informes basados en los datos.
                                            Adecuado para importar datos de evaluación.
                                        </p>
                                        
                                        <div class="mb-4">
                                            <label for="template_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Seleccione una plantilla de informe
                                            </label>
                                            <select name="template_id" id="template_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                                <option value="">Seleccione una plantilla</option>
                                                @foreach($templates as $template)
                                                    <option value="{{ $template->id }}">{{ $template->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                                            Generar Informes
                                        </button>
                                    </div>
                                </form>

                                <form action="{{ route('import.file') }}" method="POST" class="flex-1">
                                    @csrf
                                    <input type="hidden" name="csv_path" value="{{ $path }}">
                                    <input type="hidden" name="import_type" value="athletes_only">
                                    
                                    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-300 dark:border-gray-700 p-4 h-full">
                                        <h5 class="font-medium mb-2">Importar Solo Atletas</h5>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                            Crear o actualizar atletas sin generar informes.
                                            Adecuado para solo actualizar su base de datos de atletas.
                                        </p>
                                        <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                            Importar Atletas
                                        </button>
                                    </div>
                                </form>

                                <a href="{{ route('import.form') }}" class="flex-1">
                                    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-300 dark:border-gray-700 p-4 h-full">
                                        <h5 class="font-medium mb-2">Cancelar Importación</h5>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                            Cancelar el proceso de importación y volver a la página de importación.
                                            No se importará ningún dato.
                                        </p>
                                        <button type="button" class="w-full px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                                            Cancelar
                                        </button>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>