<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Editar Institución') }}
            </h2>
            <a href="{{ route('institutions.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                {{ __('Volver a Instituciones') }}
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

                    <form action="{{ route('institutions.update', $institution) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nombre de la Institución <span class="text-red-600">*</span></label>
                                <input type="text" name="name" id="name" value="{{ old('name', $institution->name) }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">El nombre de la escuela, colegio, club deportivo, etc.</p>
                            </div>
                            
                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Dirección</label>
                                <input type="text" name="address" id="address" value="{{ old('address', $institution->address) }}" 
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Dirección completa de la institución</p>
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Correo Electrónico</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $institution->email) }}" 
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Correo electrónico de contacto de la institución</p>
                            </div>
                            
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Número de Teléfono</label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone', $institution->phone) }}" 
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Número de teléfono de contacto de la institución</p>
                            </div>
                            
                            <div>
                                <label for="website" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sitio Web</label>
                                <input type="url" name="website" id="website" value="{{ old('website', $institution->website) }}" 
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">URL del sitio web de la institución</p>
                            </div>
                            
                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipo de Institución</label>
                                <select name="type" id="type" 
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                    <option value="" @if(old('type', $institution->type) == '') selected @endif>Seleccionar Tipo</option>
                                    <option value="school" @if(old('type', $institution->type) == 'school') selected @endif>Escuela</option>
                                    <option value="college" @if(old('type', $institution->type) == 'college') selected @endif>Colegio/Universidad</option>
                                    <option value="club" @if(old('type', $institution->type) == 'club') selected @endif>Club Deportivo</option>
                                    <option value="federation" @if(old('type', $institution->type) == 'federation') selected @endif>Federación Deportiva</option>
                                    <option value="academy" @if(old('type', $institution->type) == 'academy') selected @endif>Academia Deportiva</option>
                                    <option value="other" @if(old('type', $institution->type) == 'other') selected @endif>Otro</option>
                                </select>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Tipo de institución</p>
                            </div>
                        </div>
                        
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descripción</label>
                            <textarea name="description" id="description" rows="3" 
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">{{ old('description', $institution->description) }}</textarea>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Información adicional sobre la institución (opcional)</p>
                        </div>
                        
                        <div class="mt-6">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                {{ __('Actualizar Institución') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>