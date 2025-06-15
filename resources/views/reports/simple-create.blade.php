<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear Informe (Formulario Simplificado)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('reports.store') }}">
                        @csrf
                        
                        <div class="mb-6">
                            <h3 class="text-lg font-medium mb-3">Seleccione un Atleta</h3>
                            <select id="athlete_id" name="athlete_id" class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                <option value="">Seleccione un atleta</option>
                                @foreach(DB::table('athletes')
                                    ->join('athlete_profiles', 'athletes.athlete_profile_id', '=', 'athlete_profiles.id')
                                    ->select('athletes.id', 'athlete_profiles.first_name', 'athlete_profiles.last_name')
                                    ->orderBy('athlete_profiles.last_name')
                                    ->get() as $athlete)
                                    <option value="{{ $athlete->id }}">{{ $athlete->last_name }}, {{ $athlete->first_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-6">
                            <h3 class="text-lg font-medium mb-3">Seleccione una Plantilla</h3>
                            <select id="template_id" name="template_id" class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                <option value="">Seleccione una plantilla</option>
                                @foreach(DB::table('report_configs')->get() as $template)
                                    <option value="{{ $template->id }}">{{ $template->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-6">
                            <h3 class="text-lg font-medium mb-3">Datos del Informe</h3>
                            <p class="mb-4 text-gray-600">Ingrese los datos manualmente para este informe de prueba.</p>
                            
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="report_data[nombre]">
                                    Nombre
                                </label>
                                <input type="text" name="report_data[nombre]" id="report_data_nombre" class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" value="Juan">
                            </div>
                            
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="report_data[apellido]">
                                    Apellido
                                </label>
                                <input type="text" name="report_data[apellido]" id="report_data_apellido" class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" value="Pérez">
                            </div>
                            
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="report_data[documento_de_identidad]">
                                    Documento de Identidad
                                </label>
                                <input type="text" name="report_data[documento_de_identidad]" id="report_data_documento_de_identidad" class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" value="12345678">
                            </div>
                            
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="report_data[fecha_de_nacimiento]">
                                    Fecha de Nacimiento
                                </label>
                                <input type="date" name="report_data[fecha_de_nacimiento]" id="report_data_fecha_de_nacimiento" class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" value="2010-01-01">
                            </div>
                            
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="report_data[institucion]">
                                    Institución
                                </label>
                                <input type="text" name="report_data[institucion]" id="report_data_institucion" class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" value="Olympic Training Center">
                            </div>
                            
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="report_data[talla_parado]">
                                    Talla Parado (cm)
                                </label>
                                <input type="number" step="0.1" name="report_data[talla_parado]" id="report_data_talla_parado" class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" value="165.5">
                            </div>
                            
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="report_data[masa_adiposa_en_kg]">
                                    Masa Adiposa (kg)
                                </label>
                                <input type="number" step="0.1" name="report_data[masa_adiposa_en_kg]" id="report_data_masa_adiposa_en_kg" class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" value="12.5">
                            </div>
                            
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="report_data[masa_muscular_en_kg]">
                                    Masa Muscular (kg)
                                </label>
                                <input type="number" step="0.1" name="report_data[masa_muscular_en_kg]" id="report_data_masa_muscular_en_kg" class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" value="35.2">
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-end mt-6">
                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:bg-blue-600">
                                Crear Informe
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
