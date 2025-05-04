@php
use Illuminate\Support\Str;

// Define mandatory fields - these cannot be unchecked
$mandatoryFields = [
    'nombre', 
    'apellido', 
    'documento_de_identidad',
    'fecha_de_nacimiento',
    'institucion', // Institution field
];
@endphp
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear plantillas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    {{-- First Column: Form --}}
                    <div>
                        {{-- Notification Area --}}
                        @if (session('success'))
                            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded relative" role="alert">
                                <span class="block sm:inline">{{ session('success') }}</span>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative" role="alert">
                                <ul class="list-disc pl-5">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- Report Configuration Form --}}
                        <form action="{{ route('report-config.store') }}" method="POST" class="space-y-6">
                            @csrf

                            <h2 class="text-lg font-semibold mb-6">Crear Plantilla de Reporte</h2>

                            {{-- Hidden inputs for mandatory fields to ensure they're submitted even though checkboxes are disabled --}}
                            @foreach ($mandatoryFields as $field)
                                <input type="hidden" name="fields[]" value="{{ $field }}">
                            @endforeach

                            <div>
                                <x-input-label for="name" :value="__('Nombre del reporte')" class="mb-2"/>
                                <x-text-input 
                                    id="name" 
                                    name="name" 
                                    type="text" 
                                    class="mt-1 block w-full" 
                                    placeholder="Ej. Colegio Nacional Secundaria"
                                    required 
                                />
                            </div>

                            <div>
                                <h3 class="text-lg font-semibold mb-6">Seleccionar Campos</h3>
                                
                                @foreach ($fields as $section => $fieldList)
                                @php
                                    $sectionSlug = Str::slug($section);
                                    $isFirstSection = $loop->first;
                                @endphp
                                <div class="mb-6 border rounded-lg">
                                    <div 
                                        class="bg-gray-100 p-4 flex items-center justify-between cursor-pointer section-header"
                                        data-section="{{ $sectionSlug }}"
                                    >
                                            <div class="flex items-center space-x-3">
                                                <input 
                                                    type="checkbox"
                                                    class="section-select-all section-select-all-{{ $sectionSlug }} rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                                    data-section="{{ $sectionSlug }}"
                                                >
                                                <h5 class="font-semibold text-gray-700">{{ $section }}</h5>
                                            </div>
                                            
                                            <svg 
                                                class="toggle-icon w-5 h-5 transform transition-transform {{ $isFirstSection ? 'rotate-180' : '' }}" 
                                                fill="currentColor" 
                                                viewBox="0 0 20 20"
                                            >
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        {{-- Section Content --}}   
                                        
                                        <div class="section-content p-4 {{ $isFirstSection ? '' : 'hidden' }}">
                                            <div class="grid grid-cols-2 gap-3">
                                                @foreach ($fieldList as $key => $label)
                                                    <label class="flex items-center space-x-2 text-sm">
                                                        <input 
                                                            type="checkbox"
                                                            name="fields[]"
                                                            value="{{ $key }}"
                                                            class="section-checkbox section-checkbox-{{ $sectionSlug }} rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                                            data-section="{{ $sectionSlug }}"
                                                            @if (in_array($key, $mandatoryFields)) 
                                                                checked 
                                                                disabled 
                                                            @endif
                                                        >
                                                        <span>{{ $label }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <x-primary-button class="w-full justify-center">
                                {{ __('Guardar Configuración') }}
                            </x-primary-button>
                        </form>
                    </div>

                    {{-- Second Column: Saved Configurations --}}
                    <div>
                        <h2 class="text-lg font-semibold mb-6">Configuraciones Guardadas</h2>
                        <ul class="space-y-2">
                            @forelse ($configs as $config)
                                <li class="flex items-center justify-between bg-gray-50 rounded px-3 py-2">
                                    <span>{{ $config->name }}</span>
                                    <a href="{{ route('report-config.download', $config->id) }}"
                                        class="inline-flex items-center px-3 py-1 text-sm font-medium rounded bg-green-600 text-white hover:bg-green-500">
                                        Descargar plantilla
                                    </a>
                                </li>
                            @empty
                                <li class="text-sm text-gray-500">Aún no hay configuraciones guardadas.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Detailed logging for select all checkboxes
            document.querySelectorAll('.section-select-all').forEach(selectAllCheckbox => {
                const section = selectAllCheckbox.dataset.section;
                const sectionCheckboxes = document.querySelectorAll(`.section-checkbox-${section}`);
                
                console.log('Section:', section);
                console.log('Select All Checkbox:', selectAllCheckbox);
                console.log('Section Checkboxes:', sectionCheckboxes);

                selectAllCheckbox.addEventListener('change', (e) => {
                    console.log(`Select All Checkbox for ${section} changed:`, e.target.checked);
                    
                    sectionCheckboxes.forEach(checkbox => {
                        if (!checkbox.disabled) {
                            checkbox.checked = e.target.checked;
                            console.log(`Checkbox ${checkbox.value} set to ${checkbox.checked}`);
                        }
                    });
                });
            });

            // Section headers toggle
            document.querySelectorAll('.section-header').forEach(header => {
                const toggleIcon = header.querySelector('.toggle-icon');
                const content = header.nextElementSibling;
                
                header.addEventListener('click', (e) => {
                    // Prevent toggle if clicking on select-all checkbox
                    if (e.target.classList.contains('section-select-all')) return;
                    
                    content.classList.toggle('hidden');
                    toggleIcon.classList.toggle('rotate-180');
                });
            });
        });
    </script>
    @endpush
</x-app-layout>