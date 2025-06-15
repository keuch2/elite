<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Instituciones') }}
            </h2>
            <a href="{{ route('institutions.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                {{ __('Añadir Institución') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-4">
                        <form action="{{ route('institutions.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
                            <div class="flex-1">
                                <input 
                                    type="text" 
                                    name="search" 
                                    value="{{ request('search') }}" 
                                    placeholder="Buscar instituciones..." 
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                            </div>
                            <div class="flex space-x-2">
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                    {{ __('Buscar') }}
                                </button>
                                @if(request()->has('search'))
                                    <a href="{{ route('institutions.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                                        {{ __('Limpiar') }}
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>

                    @if(session('success'))
                        <div class="mb-4 px-4 py-2 bg-green-100 border border-green-400 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Nombre</th>
                                    <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Dirección</th>
                                    <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Atletas</th>
                                    <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Contacto</th>
                                    <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($institutions as $institution)
                                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">
                                            <a href="{{ route('institutions.show', $institution->id) }}" class="text-blue-600 hover:underline">
                                                {{ $institution->name }}
                                            </a>
                                        </td>
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">
                                            {{ $institution->address ?? '-' }}
                                        </td>
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">
                                            {{ $institution->athletes_count }}
                                        </td>
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">
                                            @if($institution->email)
                                                <span class="block">{{ $institution->email }}</span>
                                            @endif
                                            @if($institution->phone)
                                                <span class="block">{{ $institution->phone }}</span>
                                            @endif
                                            @if(!$institution->email && !$institution->phone)
                                                <span>-</span>
                                            @endif
                                        </td>
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('institutions.show', $institution->id) }}" class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-600 rounded hover:bg-blue-200 dark:bg-blue-900 dark:text-blue-300 dark:hover:bg-blue-800">Ver</a>
                                                <a href="{{ route('institutions.edit', $institution->id) }}" class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-600 rounded hover:bg-yellow-200 dark:bg-yellow-900 dark:text-yellow-300 dark:hover:bg-yellow-800">Editar</a>
                                                <form action="{{ route('institutions.destroy', $institution->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="px-2 py-1 text-xs font-medium bg-red-100 text-red-600 rounded hover:bg-red-200 dark:bg-red-900 dark:text-red-300 dark:hover:bg-red-800" onclick="return confirm('¿Está seguro de que quiere eliminar esta institución? Esto no eliminará los atletas asociados pero eliminará la asociación.')">
                                                        Eliminar
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-4 px-4 text-center text-gray-500 dark:text-gray-400">
                                            No se encontraron instituciones.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $institutions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>