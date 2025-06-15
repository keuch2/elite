<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $institution->name }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ url('/institutions/'.$institution->id.'/reports') }}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                    {{ __('Reportes') }}
                </a>
                <a href="{{ url('/institutions/'.$institution->id.'/edit') }}" class="px-4 py-2 bg-amber-600 text-white rounded-md hover:bg-amber-700">
                    {{ __('Editar Institución') }}
                </a>
                <a href="{{ url('/institutions') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                    {{ __('Volver a Instituciones') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">Detalles de la Institución</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded">
                                <div class="mb-2">
                                    <span class="block text-sm text-gray-500 dark:text-gray-400">Nombre</span>
                                    <span class="font-medium">{{ $institution->name }}</span>
                                </div>
                                
                                <div class="mb-2">
                                    <span class="block text-sm text-gray-500 dark:text-gray-400">Dirección</span>
                                    <span class="font-medium">{{ $institution->address ?? 'No especificado' }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded">
                                <div class="mb-2">
                                    <span class="block text-sm text-gray-500 dark:text-gray-400">Correo electrónico</span>
                                    <span class="font-medium">{{ $institution->email ?? 'No especificado' }}</span>
                                </div>
                                
                                <div class="mb-2">
                                    <span class="block text-sm text-gray-500 dark:text-gray-400">Teléfono</span>
                                    <span class="font-medium">{{ $institution->phone ?? 'No especificado' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end">
                        <a href="{{ route('institutions.edit', $institution->id) }}" class="px-4 py-2 bg-yellow-500 text-white rounded-md mr-2">Editar</a>
                        <form action="{{ route('institutions.destroy', $institution->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-md" onclick="return confirm('¿Estás seguro de que deseas eliminar esta institución? Esto no eliminará a los atletas asociados pero eliminará la asociación.')">
                                Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium">Atletas ({{ $athletes->total() }})</h3>
                        <div>
                            <form action="{{ url('/institutions/'.$institution->id) }}" method="GET" class="flex space-x-2">
                                <input 
                                    type="text" 
                                    name="search" 
                                    value="{{ request('search') }}"
                                    placeholder="Buscar atletas..." 
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                    {{ __('Buscar') }}
                                </button>
                                @if(request()->has('search'))
                                    <a href="{{ url('/institutions/'.$institution->id) }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
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
                                    <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Nombre</th>
                                    <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">ID</th>
                                    <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Género</th>
                                    <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Edad</th>
                                    <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Deporte</th>
                                    <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($athletes as $athlete)
                                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">
                                            {{ $athlete->athleteProfile->first_name ?? '' }} {{ $athlete->athleteProfile->last_name ?? '' }}
                                        </td>
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">
                                            {{ $athlete->athleteProfile->identity_document ?? '-' }}
                                        </td>
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">
                                            @if($athlete->athleteProfile && $athlete->athleteProfile->gender === 'm')
                                                Masculino
                                            @elseif($athlete->athleteProfile && $athlete->athleteProfile->gender === 'f') 
                                                Femenino
                                            @else 
                                                Otro
                                            @endif
                                        </td>
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">
                                            @if($athlete->athleteProfile && $athlete->athleteProfile->birth_date)
                                                {{ \Carbon\Carbon::parse($athlete->athleteProfile->birth_date)->age }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">
                                            {{ $athlete->athleteProfile->sport ?? '-' }}
                                        </td>
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 flex space-x-2">
                                            <a href="{{ url('/athletes/'.$athlete->id) }}" class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-600 rounded hover:bg-blue-200 dark:bg-blue-900 dark:text-blue-300 dark:hover:bg-blue-800">Ver</a>
                                            <a href="{{ url('/athletes/'.$athlete->id.'/edit') }}" class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-600 rounded hover:bg-yellow-200 dark:bg-yellow-900 dark:text-yellow-300 dark:hover:bg-yellow-800">Editar</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-4 px-4 text-center text-gray-500 dark:text-gray-400">
                                            No se encontraron atletas para esta institución.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $athletes->links() }}
                    </div>

                    <div class="mt-6">
                        <a href="{{ url('/athletes/create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            {{ __('Crear Atleta') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>