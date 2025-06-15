<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Atletas') }}
            </h2>
            <a href="{{ route('athletes.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                {{ __('Añadir Nuevo Atleta') }}
            </a>
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

                    <div class="mb-4">
                        <form action="{{ route('athletes.index') }}" method="GET" class="flex items-center space-x-4">
                            <div class="flex-1">
                                <label for="institution_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Filtrar por Institución</label>
                                <select name="institution_id" id="institution_id" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                    <option value="">Todas las Instituciones</option>
                                    @foreach($institutions as $institution)
                                        <option value="{{ $institution->id }}" @selected(request('institution_id') == $institution->id)>{{ $institution->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="pt-6">
                                <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                                    {{ __('Filtrar') }}
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Nombre</th>
                                    <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Género</th>
                                    <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Documento ID</th>
                                    <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Fecha de Nacimiento</th>
                                    <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Institución</th>
                                    <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Reportes</th>
                                    <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Último Reporte</th>
                                    <th class="py-2 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($athletes as $athlete)
                                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">
                                            <a href="{{ route('athletes.show', $athlete->id) }}" class="text-blue-600 hover:underline">
                                                {{ $athlete->first_name }} {{ $athlete->last_name }}
                                            </a>
                                        </td>
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">
                                            {{ $athlete->gender == 'm' ? 'Masculino' : ($athlete->gender == 'f' ? 'Femenino' : 'Otro') }}
                                        </td>
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">
                                            {{ $athlete->identity_document ?? '-' }}
                                        </td>
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">
                                            {{ $athlete->birth_date ? date('d/m/Y', strtotime($athlete->birth_date)) : '-' }}
                                        </td>
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">
                                            {{ $athlete->institution_name ?? '-' }}
                                        </td>
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">
                                            {{ $athlete->evaluations_count ?? '1' }}
                                        </td>
                                        <td class="py-2 px-4 border-b border-gray-300 dark:border-gray-700">
                                            {{ $athlete->latest_evaluation ? date('d/m/Y', strtotime($athlete->latest_evaluation)) : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('athletes.show', $athlete->id) }}" class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-600 rounded hover:bg-blue-200 dark:bg-blue-900 dark:text-blue-300 dark:hover:bg-blue-800 mr-1">Ver</a>
                                            <a href="{{ route('athletes.edit', $athlete->id) }}" class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-600 rounded hover:bg-yellow-200 dark:bg-yellow-900 dark:text-yellow-300 dark:hover:bg-yellow-800 mr-1">Editar</a>
                                            <form class="inline" method="POST" action="{{ route('athletes.destroy', $athlete->id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-2 py-1 text-xs font-medium bg-red-100 text-red-600 rounded hover:bg-red-200 dark:bg-red-900 dark:text-red-300 dark:hover:bg-red-800" onclick="return confirm('¿Está seguro que desea eliminar esta evaluación?')">Eliminar</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="py-4 px-4 text-center text-gray-500 dark:text-gray-400">
                                            No se encontraron atletas.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <div class="mt-4">
                            {{ $athletes->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>