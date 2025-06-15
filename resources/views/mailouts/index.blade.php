<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Envío Masivo de Informes') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">Seleccionar Atletas para Envío de Informes</h3>
                    
                    <div class="mb-6">
                        <label for="institution_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Institución</label>
                        <select id="institution_id" name="institution_id" class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="">Seleccione una institución</option>
                            @foreach($institutions as $institution)
                                <option value="{{ $institution->id }}">{{ $institution->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div id="athletesContainer" class="hidden">
                        <form id="mailoutForm" action="{{ url('/mailouts/send') }}" method="POST">
                            @csrf
                            
                            <div class="mb-4">
                                <div class="flex justify-between items-center mb-2">
                                    <h4 class="text-md font-medium">Atletas Disponibles</h4>
                                    <div>
                                        <button type="button" id="selectAllBtn" class="text-sm text-blue-600 hover:text-blue-800">Seleccionar Todos</button>
                                        <button type="button" id="deselectAllBtn" class="ml-2 text-sm text-blue-600 hover:text-blue-800">Deseleccionar Todos</button>
                                    </div>
                                </div>
                                
                                <div class="border rounded-md overflow-hidden">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50 dark:bg-gray-700">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Seleccionar
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Nombre
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Deporte
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Categoría
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Último Informe
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody id="athletesList" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                            <!-- Athletes will be loaded here via JavaScript -->
                                            <tr>
                                                <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                                    Seleccione una institución para ver los atletas
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <div id="emailDetailsSection" class="hidden mt-8 border-t pt-6">
                                <h4 class="text-md font-medium mb-4">Detalles del Correo</h4>
                                
                                <div class="mb-4">
                                    <label for="subject" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Asunto</label>
                                    <input type="text" name="subject" id="subject" required
                                        class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        placeholder="Informe de Evaluación Deportiva">
                                </div>
                                
                                <div class="mb-4">
                                    <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mensaje</label>
                                    <textarea name="message" id="message" rows="5" required
                                        class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        placeholder="Estimado tutor, adjuntamos el informe de evaluación deportiva de su atleta. Puede acceder al informe a través del enlace proporcionado."></textarea>
                                </div>
                                
                                <div class="flex justify-end">
                                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                        Enviar Correos
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <div id="noAthletesMessage" class="hidden mt-4 text-center py-8 text-gray-500 dark:text-gray-400">
                        <p>No hay atletas con informes disponibles para esta institución.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const institutionSelect = document.getElementById('institution_id');
            const athletesContainer = document.getElementById('athletesContainer');
            const athletesList = document.getElementById('athletesList');
            const emailDetailsSection = document.getElementById('emailDetailsSection');
            const noAthletesMessage = document.getElementById('noAthletesMessage');
            const selectAllBtn = document.getElementById('selectAllBtn');
            const deselectAllBtn = document.getElementById('deselectAllBtn');
            const mailoutForm = document.getElementById('mailoutForm');
            
            // Load athletes when institution is selected
            institutionSelect.addEventListener('change', function() {
                const institutionId = this.value;
                
                if (!institutionId) {
                    athletesContainer.classList.add('hidden');
                    noAthletesMessage.classList.add('hidden');
                    return;
                }
                
                // Show loading state
                athletesList.innerHTML = `
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-500 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Cargando atletas...
                        </td>
                    </tr>
                `;
                
                // Fetch athletes for the selected institution
                fetch(`{{ url('/') }}/mailouts/athletes/${institutionId}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Error fetching athletes');
                        }
                        return response.json();
                    })
                    .then(athletes => {
                        if (athletes.length === 0) {
                            athletesContainer.classList.add('hidden');
                            noAthletesMessage.classList.remove('hidden');
                            return;
                        }
                        
                        // Filter athletes with reports
                        const athletesWithReports = athletes.filter(athlete => athlete.has_report);
                        
                        if (athletesWithReports.length === 0) {
                            athletesContainer.classList.add('hidden');
                            noAthletesMessage.classList.remove('hidden');
                            return;
                        }
                        
                        // Show athletes with reports
                        athletesList.innerHTML = '';
                        athletesWithReports.forEach(athlete => {
                            athletesList.innerHTML += `
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="checkbox" name="athlete_ids[]" value="${athlete.id}" class="athlete-checkbox rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        ${athlete.name}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        ${athlete.sport || 'N/A'}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        ${athlete.category || 'N/A'}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        ${athlete.report_date || 'N/A'}
                                    </td>
                                </tr>
                            `;
                        });
                        
                        athletesContainer.classList.remove('hidden');
                        noAthletesMessage.classList.add('hidden');
                        
                        // Add event listeners to checkboxes
                        const checkboxes = document.querySelectorAll('.athlete-checkbox');
                        checkboxes.forEach(checkbox => {
                            checkbox.addEventListener('change', updateEmailDetailsVisibility);
                        });
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        athletesList.innerHTML = `
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-red-500">
                                    Error al cargar los atletas. Por favor, inténtelo de nuevo.
                                </td>
                            </tr>
                        `;
                    });
            });
            
            // Select/deselect all checkboxes
            selectAllBtn.addEventListener('click', function() {
                const checkboxes = document.querySelectorAll('.athlete-checkbox');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = true;
                });
                updateEmailDetailsVisibility();
            });
            
            deselectAllBtn.addEventListener('click', function() {
                const checkboxes = document.querySelectorAll('.athlete-checkbox');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });
                updateEmailDetailsVisibility();
            });
            
            // Show/hide email details section based on checkbox selection
            function updateEmailDetailsVisibility() {
                const checkboxes = document.querySelectorAll('.athlete-checkbox:checked');
                if (checkboxes.length > 0) {
                    emailDetailsSection.classList.remove('hidden');
                } else {
                    emailDetailsSection.classList.add('hidden');
                }
            }
            
            // Form submission validation
            mailoutForm.addEventListener('submit', function(e) {
                const checkboxes = document.querySelectorAll('.athlete-checkbox:checked');
                if (checkboxes.length === 0) {
                    e.preventDefault();
                    alert('Por favor, seleccione al menos un atleta para enviar el informe.');
                }
            });
        });
    </script>
</x-app-layout>
