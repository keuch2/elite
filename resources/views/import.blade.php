<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Importar Datos de Atletas') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
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

                    <div class="mb-8">
                        <h3 class="text-lg font-medium mb-4">Subir Archivo</h3>
                        <p class="mb-4 text-gray-600 dark:text-gray-400">
                            Sube un archivo CSV o Excel que contenga datos de los atletas. El sistema admite ambos formatos y procesará los datos en consecuencia.
                        </p>

                        <form action="{{ route('import.file') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            
                            <div>
                                <label for="file" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Seleccionar Archivo (CSV, XLSX, XLS) <span class="text-red-600">*</span></label>
                                <input type="file" name="file" id="file" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 dark:border-gray-700 dark:bg-gray-900" required accept=".csv,.xlsx,.xls">
                                @error('file')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                    {{ __('Subir y Procesar') }}
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="border-t border-gray-200 dark:border-gray-700 pt-8">
                        <h3 class="text-lg font-medium mb-4">Requisitos del Archivo</h3>

                        <div class="prose dark:prose-invert">
                            <h4>Columnas Requeridas para Importar Atletas</h4>
                            <ul class="list-disc pl-5 space-y-1">
                                <li>first_name - Nombre del atleta</li>
                                <li>last_name - Apellido del atleta</li>
                                <li>gender - Género como 'm' para masculino, 'f' para femenino, o 'o' para otro</li>
                                <li>identity_document - Documento de identidad o cédula (debe ser único)</li>
                                <li>birth_date - Fecha de nacimiento en formato AAAA-MM-DD</li>
                                <li>institution_name - Nombre de la institución (se creará si no existe)</li>
                            </ul>

                            <h4 class="mt-4">Columnas Opcionales</h4>
                            <ul class="list-disc pl-5 space-y-1">
                                <li>father_name - Nombre completo del padre</li>
                                <li>mother_name - Nombre completo de la madre</li>
                                <li>grade - Grado escolar/año actual</li>
                                <li>sport - Deporte principal practicado</li>
                                <li>category - Categoría/división deportiva</li>
                                <li>weight - Peso del atleta en kg</li>
                                <li>standing_height - Altura de pie en cm</li>
                                <li>sitting_height - Altura sentado en cm</li>
                                <li>wingspan - Envergadura en cm</li>
                            </ul>
                            
                            <h4 class="mt-4">Ejemplo</h4>
                            <p>Puede <a href="#" class="text-blue-600 hover:underline">descargar una plantilla CSV de ejemplo aquí</a> para ver el formato esperado.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>