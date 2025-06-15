<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear Informe') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form id="reportForm" method="POST" action="{{ route('reports.store') }}">
                        @csrf
                        
                        <!-- Step 1: Select Institution -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium mb-3">Paso 1: Seleccione una Institución</h3>
                            <select id="institution_id" name="institution_id" class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                <option value="">Seleccione una institución</option>
                                @foreach($institutions as $institution)
                                    <option value="{{ $institution->id }}">{{ $institution->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Step 2: Select Athlete -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium mb-3">Paso 2: Seleccione un Atleta</h3>
                            <select id="athlete_id" name="athlete_id" class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required disabled>
                                <option value="">Primero seleccione una institución</option>
                            </select>
                        </div>
                        
                        <!-- Step 3: Select Template -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium mb-3">Paso 3: Seleccione una Plantilla</h3>
                            <select id="template_id" name="template_id" class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required disabled>
                                <option value="">Primero seleccione un atleta</option>
                            </select>
                        </div>
                        
                        <!-- Report Data Section -->
                        <div id="reportDataSection" class="mt-8" style="display: none;">
                            <h2 class="text-xl font-bold mb-4">Datos del Informe</h2>
                            <p class="mb-4 text-gray-600">Los campos disponibles se cargarán según la plantilla seleccionada.</p>
                            
                            <div id="reportFields"></div>
                        </div>
                        
                        <div class="flex items-center justify-end mt-6">
                            <button id="submitBtn" type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:bg-blue-600" disabled>
                                {{ __('Guardar Informe') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM fully loaded - initializing form handlers');
            
            const institutionSelect = document.getElementById('institution_id');
            const athleteSelect = document.getElementById('athlete_id');
            const templateSelect = document.getElementById('template_id');
            const reportDataSection = document.getElementById('reportDataSection');
            const reportFields = document.getElementById('reportFields');
            const submitBtn = document.getElementById('submitBtn');
            
            // Initialize templates data
            const templates = @json($templates);
            console.log('Templates loaded:', templates);
            
            // Step 1: When institution is selected, load athletes
            institutionSelect.addEventListener('change', function() {
                const institutionId = this.value;
                console.log('Institution selected:', institutionId);
                
                if (institutionId) {
                    // Reset next steps
                    athleteSelect.innerHTML = '<option value="">Seleccionando atletas...</option>';
                    athleteSelect.disabled = true;
                    templateSelect.innerHTML = '<option value="">Primero seleccione un atleta</option>';
                    templateSelect.disabled = true;
                    reportDataSection.style.display = 'none';
                    submitBtn.disabled = true;
                    
                    // Fetch athletes by institution
                    const fetchUrl = `{{ url('/reports/athletes-by-institution') }}/${institutionId}`;
                    console.log('Fetching athletes from:', fetchUrl);
                    
                    fetch(fetchUrl)
                        .then(response => {
                            console.log('Athletes fetch response status:', response.status);
                            if (!response.ok) {
                                throw new Error(`HTTP error! Status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('Athletes data received:', data);
                            athleteSelect.innerHTML = '<option value="">Seleccione un atleta</option>';
                            
                            data.forEach(athlete => {
                                const athleteOption = document.createElement('option');
                                athleteOption.value = athlete.id;
                                athleteOption.textContent = `${athlete.last_name}, ${athlete.first_name}`;
                                athleteSelect.appendChild(athleteOption);
                            });
                            
                            athleteSelect.disabled = false;
                            console.log('Athlete dropdown populated with', data.length, 'options');
                        })
                        .catch(error => {
                            console.error('Error fetching athletes:', error);
                            athleteSelect.innerHTML = '<option value="">Error al cargar atletas</option>';
                        });
                } else {
                    // Reset everything if no institution selected
                    athleteSelect.innerHTML = '<option value="">Primero seleccione una institución</option>';
                    athleteSelect.disabled = true;
                    templateSelect.disabled = true;
                    reportDataSection.style.display = 'none';
                    submitBtn.disabled = true;
                    console.log('Institution deselected - form reset');
                }
            });
            
            // Step 2: When athlete is selected, enable template selection
            athleteSelect.addEventListener('change', function() {
                const athleteId = this.value;
                console.log('Athlete selected:', athleteId);
                
                if (athleteId) {
                    // Enable template selection
                    templateSelect.disabled = false;
                    templateSelect.innerHTML = '<option value="">Seleccione una plantilla</option>';
                    
                    // Add template options back
                    templates.forEach(template => {
                        const templateOption = document.createElement('option');
                        templateOption.value = template.id;
                        templateOption.textContent = template.name;
                        templateSelect.appendChild(templateOption);
                    });
                    console.log('Template dropdown populated with', templates.length, 'options');
                } else {
                    templateSelect.disabled = true;
                    templateSelect.innerHTML = '<option value="">Primero seleccione un atleta</option>';
                    reportDataSection.style.display = 'none';
                    submitBtn.disabled = true;
                    console.log('Athlete deselected - template options reset');
                }
            });
            
            // Step 3: When template is selected, load report fields
            templateSelect.addEventListener('change', function() {
                const templateId = this.value;
                const athleteId = athleteSelect.value;
                console.log('Template selected:', templateId, 'for athlete:', athleteId);
                
                if (templateId && athleteId) {
                    // Show loading state
                    reportFields.innerHTML = '<p class="text-gray-600">Cargando campos del informe...</p>';
                    reportDataSection.style.display = 'block';
                    
                    // Fetch template fields - use the full URL to avoid path issues
                    const templateUrl = `{{ url('/report-templates') }}/${templateId}/fields`;
                    console.log('Fetching template fields from:', templateUrl);
                    
                    fetch(templateUrl)
                        .then(response => {
                            console.log('Template fields response status:', response.status);
                            if (!response.ok) {
                                throw new Error('Error fetching template fields: ' + response.statusText);
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('Template fields data received:', data);
                            reportFields.innerHTML = '';
                            
                            // Fetch athlete details to autofill the form
                            const athleteUrl = `{{ url('/reports/athlete-details') }}/${athleteId}`;
                            console.log('Fetching athlete details from:', athleteUrl);
                            
                            fetch(athleteUrl)
                                .then(response => {
                                    console.log('Athlete details response status:', response.status);
                                    if (!response.ok) {
                                        throw new Error('Error fetching athlete details: ' + response.statusText);
                                    }
                                    return response.json();
                                })
                                .then(athleteData => {
                                    console.log("Template fields:", data);
                                    console.log("Athlete data:", athleteData);
                                    
                                    // Process template fields
                                    if (data && typeof data === 'object') {
                                        Object.keys(data).forEach(category => {
                                            // Create category header
                                            const categoryDiv = document.createElement('div');
                                            categoryDiv.className = 'mb-6';
                                            
                                            const categoryHeader = document.createElement('h3');
                                            categoryHeader.className = 'text-lg font-medium mb-3';
                                            categoryHeader.textContent = category;
                                            categoryDiv.appendChild(categoryHeader);
                                            
                                            // Create fields for this category
                                            data[category].forEach(field => {
                                                const fieldDiv = document.createElement('div');
                                                fieldDiv.className = 'mb-4';
                                                
                                                const label = document.createElement('label');
                                                label.className = 'block text-gray-700 text-sm font-bold mb-2';
                                                label.htmlFor = `report_data[${field}]`;
                                                label.textContent = field.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                                                fieldDiv.appendChild(label);
                                                
                                                const input = document.createElement('input');
                                                input.type = 'text';
                                                input.className = 'w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50';
                                                input.name = `report_data[${field}]`;
                                                input.id = `report_data_${field}`;
                                                
                                                // Autofill with athlete data if available
                                                if (athleteData[field]) {
                                                    console.log(`Setting field ${field} to value:`, athleteData[field]);
                                                    input.value = athleteData[field];
                                                } else {
                                                    console.log(`No data found for field ${field}`);
                                                }
                                                
                                                fieldDiv.appendChild(input);
                                                categoryDiv.appendChild(fieldDiv);
                                            });
                                            
                                            reportFields.appendChild(categoryDiv);
                                        });
                                        
                                        // Enable the submit button
                                        submitBtn.disabled = false;
                                        console.log('Form fields populated successfully, submit button enabled');
                                    } else {
                                        reportFields.innerHTML = '<p class="text-red-500">No se encontraron campos para esta plantilla.</p>';
                                        console.error('Invalid template fields data:', data);
                                    }
                                })
                                .catch(error => {
                                    console.error('Error fetching athlete details:', error);
                                    reportFields.innerHTML = '<p class="text-red-500">Error al cargar los datos del atleta: ' + error.message + '</p>';
                                });
                        })
                        .catch(error => {
                            console.error('Error fetching template fields:', error);
                            reportFields.innerHTML = '<p class="text-red-500">Error al cargar los campos de la plantilla: ' + error.message + '</p>';
                        });
                } else {
                    reportDataSection.style.display = 'none';
                    submitBtn.disabled = true;
                    console.log('Template or athlete deselected - form fields reset');
                }
            });
        });
    </script>
</x-app-layout>
