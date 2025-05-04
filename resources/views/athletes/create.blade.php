<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Añadir Nuevo Atleta') }}
            </h2>
            <a href="{{ route('athletes.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                {{ __('Volver a Atletas') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('athletes.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-6">
                            <h3 class="text-lg font-medium mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">Información Personal</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label for="first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nombre <span class="text-red-600">*</span></label>
                                    <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" required
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                </div>
                                
                                <div>
                                    <label for="last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Apellido <span class="text-red-600">*</span></label>
                                    <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" required
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                </div>
                                
                                <div>
                                    <label for="identity_document" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Documento de Identidad</label>
                                    <input type="text" name="identity_document" id="identity_document" value="{{ old('identity_document') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">DNI, pasaporte, etc.</p>
                                </div>
                                
                                <div>
                                    <label for="gender" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Género <span class="text-red-600">*</span></label>
                                    <select name="gender" id="gender" required
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                        <option value="">Seleccionar Género</option>
                                        <option value="m" {{ old('gender') == 'm' ? 'selected' : '' }}>Masculino</option>
                                        <option value="f" {{ old('gender') == 'f' ? 'selected' : '' }}>Femenino</option>
                                        <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Otro</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="birth_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fecha de Nacimiento <span class="text-red-600">*</span></label>
                                    <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date') }}" required
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                </div>
                                
                                <div>
                                    <label for="institution_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Institución</label>
                                    <select name="institution_id" id="institution_id"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                        <option value="">Seleccionar Institución</option>
                                        @foreach($institutions as $institution)
                                            <option value="{{ $institution->id }}" {{ old('institution_id') == $institution->id ? 'selected' : '' }}>
                                                {{ $institution->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Escuela, club, etc. al que pertenece el atleta</p>
                                </div>
                                
                                <div>
                                    <label for="father_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nombre del Padre</label>
                                    <input type="text" name="father_name" id="father_name" value="{{ old('father_name') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                </div>
                                
                                <div>
                                    <label for="mother_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nombre de la Madre</label>
                                    <input type="text" name="mother_name" id="mother_name" value="{{ old('mother_name') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <h3 class="text-lg font-medium mb-4 pb-2 border-b border-gray-200 dark:border-gray-700">Información de Evaluación</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label for="evaluation_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fecha de Evaluación <span class="text-red-600">*</span></label>
                                    <input type="date" name="evaluation_date" id="evaluation_date" value="{{ old('evaluation_date', date('Y-m-d')) }}" required
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                </div>
                                
                                <div>
                                    <label for="age" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Edad <span class="text-red-600">*</span></label>
                                    <input type="number" name="age" id="age" value="{{ old('age') }}" required min="1" max="120"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                </div>
                                
                                <div>
                                    <label for="grade" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Grado/Año</label>
                                    <input type="text" name="grade" id="grade" value="{{ old('grade') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Grado académico o año</p>
                                </div>
                                
                                <div>
                                    <label for="sport" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deporte</label>
                                    <input type="text" name="sport" id="sport" value="{{ old('sport') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Deporte principal del atleta</p>
                                </div>
                                
                                <div>
                                    <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Categoría</label>
                                    <input type="text" name="category" id="category" value="{{ old('category') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Grupo de edad, categoría de peso o división competitiva</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                {{ __('Crear Atleta') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>