@extends('main')

@section('title', 'Pets')

@section('content')
<div class="tw-p-6 tw-bg-gradient-to-tl tw-h-screen tw-overflow-y-auto tw-to-[#b7f4ff] tw-from-white">
    <!-- Header with Profile -->
    <div class="tw-flex tw-justify-between tw-items-center tw-mb-6">
        <div>
            <p class="tw-text-sm tw-text-gray-500">Pages / Pets</p>
            <h1 class="tw-text-2xl tw-font-bold">Registered Pets</h1>
        </div>
        <div class="tw-flex tw-items-center tw-gap-4">
            <button type="button" data-modal-target="addPet-modal" data-modal-toggle="addPet-modal" 
                class="tw-bg-[#24CFF4] tw-text-white tw-px-4 tw-py-2 tw-rounded-xl tw-transition-all tw-duration-300 hover:tw-shadow-lg hover:tw-opacity-90 tw-font-semibold">
                <i class="fas fa-plus tw-mr-2"></i> Register New Pet
            </button>
            <div class="tw-flex tw-items-center tw-justify-end tw-bg-white tw-py-1 tw-px-4 tw-rounded-full tw-shadow-md tw-gap-4 tw-transition-all tw-duration-300 tw-ease-in-out hover:tw-shadow-lg">
                <span class="tw-text-gray-700 tw-font-medium">{{ Auth::user()->firstName }} {{ Auth::user()->lastName }}</span>
                <div class="tw-relative">
                    <img src="{{ asset('storage/' . Auth::user()->userImage) }}" alt="User Avatar" 
                        class="tw-w-10 tw-h-10 tw-rounded-full tw-cursor-pointer tw-transition-all tw-duration-300 hover:tw-brightness-75 tw-object-cover" 
                        onclick="toggleDropdown()">
                    <div id="dropdown" class="tw-absolute tw-rounded-3xl tw-right-0 tw-z-20 tw-mt-2 tw-w-48 tw-bg-white tw-rounded-md tw-shadow-lg tw-hidden">
                        <a href="{{ route('content.account') }}" class="tw-block tw-px-4 tw-py-2 tw-text-gray-700 hover:tw-bg-gray-100">Account Settings</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="tw-block tw-w-full tw-text-left tw-px-4 tw-py-2 tw-text-gray-700 hover:tw-bg-gray-100">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="tw-bg-white tw-rounded-2xl tw-p-4 tw-mb-6 tw-shadow-sm">
        <div class="row tw-items-center">
            <div class="col-12 col-md-4 tw-mb-3 tw-mb-md-0">
                <input type="text" id="searchPet" placeholder="Search pets..." 
                    class="tw-w-full tw-px-4 tw-py-2 tw-rounded-xl tw-border tw-border-gray-200 focus:tw-border-[#24CFF4] focus:tw-ring-1 focus:tw-ring-[#24CFF4]">
            </div>
            <div class="col-12 col-md-8">
                <div class="tw-flex tw-flex-wrap tw-gap-2">
                    <button class="tw-px-4 tw-py-2 tw-rounded-xl tw-text-sm tw-font-medium tw-transition-all species-filter active"
                            data-species="all">All</button>
                    <button class="tw-px-4 tw-py-2 tw-rounded-xl tw-text-sm tw-font-medium tw-transition-all species-filter"
                            data-species="Dog">Dogs</button>
                    <button class="tw-px-4 tw-py-2 tw-rounded-xl tw-text-sm tw-font-medium tw-transition-all species-filter"
                            data-species="Cat">Cats</button>
                    <button class="tw-px-4 tw-py-2 tw-rounded-xl tw-text-sm tw-font-medium tw-transition-all species-filter"
                            data-species="Rabbit">Rabbits</button>
                    <button class="tw-px-4 tw-py-2 tw-rounded-xl tw-text-sm tw-font-medium tw-transition-all species-filter"
                            data-species="Hamster">Hamsters</button>
                    <button class="tw-px-4 tw-py-2 tw-rounded-xl tw-text-sm tw-font-medium tw-transition-all species-filter"
                            data-species="Guinea Pig">Guinea Pigs</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Pet Cards Grid -->
    <div class="row g-4">
        @forelse($pets as $pet)
        <div class="col-12 col-md-6 col-lg-4 col-xl-3">
            <div class="tw-bg-white tw-rounded-2xl tw-overflow-hidden tw-shadow-sm tw-transition-all tw-duration-300 hover:tw-shadow-lg hover:-tw-translate-y-1 tw-relative tw-group">
                <form method="POST" action="{{ route('pets.delete', $pet->petID) }}" class="tw-absolute tw-top-3 tw-left-3 tw-z-20 tw-opacity-0 group-hover:tw-opacity-100 tw-transition-all tw-duration-200">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="tw-bg-red-500 tw-text-white tw-rounded-full tw-w-8 tw-h-8 tw-flex tw-items-center tw-justify-center hover:tw-bg-red-600 tw-shadow-md" onclick="return confirm('Are you sure you want to delete this pet?')">
                        <i class="fas fa-trash-alt tw-text-sm"></i>
                    </button>
                </form>
                <div class="tw-relative">
                    <img src="{{ asset('storage/' . $pet->petImage) }}" 
                        alt="{{ $pet->name }}" 
                        class="tw-w-full tw-h-48 tw-object-cover">
                    <div class="tw-absolute tw-top-3 tw-right-3">
                        <span class="tw-px-3 tw-py-1 tw-rounded-full tw-text-sm tw-bg-white/90 tw-backdrop-blur-sm 
                            @if(trim(strtolower($pet->species)) === 'dog') tw-text-green-800
                            @elseif(trim(strtolower($pet->species)) === 'cat') tw-text-yellow-800
                            @else tw-text-blue-800 @endif">
                            {{ $pet->species }}
                        </span>
                    </div>
                </div>
                <div class="tw-p-4">
                    <div class="tw-flex tw-justify-between tw-items-start tw-mb-3">
                        <h3 class="tw-text-xl tw-font-semibold">{{ $pet->name }}</h3>
                        <span class="tw-text-sm tw-text-gray-500">{{ $pet->breed }}</span>
                    </div>
                    <div class="tw-space-y-2 tw-mb-4">
                        <!-- Age calculation is now handled by the Pet model's getAgeAttribute -->
                        <div class="tw-flex tw-items-center tw-gap-2">
                            <i class="fas fa-birthday-cake tw-text-gray-400"></i>
                            <span class="tw-text-sm tw-text-gray-600">{{ $pet->age }}</span>
                        </div>
                        <!-- Add gender information -->
                        <div class="tw-flex tw-items-center tw-gap-2">
                            <i class="fas fa-venus-mars tw-text-gray-400"></i>
                            <span class="tw-text-sm tw-text-gray-600">{{ $pet->gender }}</span>
                        </div>
                        <!-- Add weight information -->
                        <div class="tw-flex tw-items-center tw-gap-2">
                            <i class="fas fa-weight tw-text-gray-400"></i>
                            <span class="tw-text-sm tw-text-gray-600">{{ number_format($pet->weight, 2) }} kg</span>
                        </div>
                        <!-- Add vaccination status -->
                        <div class="tw-flex tw-items-center tw-gap-2">
                            <i class="fas fa-syringe tw-text-gray-400"></i>
                            <span class="tw-text-sm tw-text-gray-600">
                                @if($pet->isVaccinated)
                                    <span class="tw-text-green-600">Vaccinated</span>
                                    <!-- Only show last vaccination date if vaccinated -->
                                    <span class="tw-text-xs tw-text-gray-500">({{ \Carbon\Carbon::parse($pet->lastVaccinationDate)->format('M d, Y') }})</span>
                                @else
                                    <span class="tw-text-red-600">Not Vaccinated</span>
                                @endif
                            </span>
                        </div>
                        <!-- Notes with truncation -->
                        <div class="tw-flex tw-items-start tw-gap-2">
                            <i class="fas fa-sticky-note tw-text-gray-400 tw-mt-1"></i>
                            <p class="tw-text-sm tw-text-gray-600 tw-line-clamp-2">
                                {{ $pet->petNotes ?: 'No notes available' }}
                            </p>
                        </div>
                    </div>
                    <div class="tw-flex tw-justify-between tw-items-center">
                        <button onclick="viewPet({{ $pet->petID }})" 
                                class="tw-text-[#24CFF4] tw-text-sm hover:tw-underline">
                            View Details
                        </button>
                        <button type="button" onclick="editPet({{ $pet->petID }})" 
                                class="tw-bg-[#24CFF4] tw-text-white tw-px-4 tw-py-2 tw-rounded-xl tw-transition-all tw-duration-300 hover:tw-shadow-lg hover:tw-opacity-90" 
                                data-modal-target="editPet-modal" data-modal-toggle="editPet-modal">
                            <i class="fas fa-edit tw-mr-2"></i>Edit
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="tw-flex tw-flex-col tw-items-center tw-justify-center tw-bg-white tw-rounded-2xl tw-p-8 tw-shadow-sm">
                <i class="fas fa-paw tw-text-5xl tw-text-gray-300 tw-mb-4"></i>
                <p class="tw-text-gray-500 tw-mb-4">No pets registered yet</p>
                <button type="button" data-modal-target="addPet-modal" data-modal-toggle="addPet-modal" 
                        class="tw-bg-[#24CFF4] tw-text-white tw-px-6 tw-py-2 tw-rounded-xl tw-transition-all tw-duration-300 hover:tw-shadow-lg hover:tw-opacity-90">
                    <i class="fas fa-plus tw-mr-2"></i>Add Pet
                </button>
            </div>
        </div>
        @endforelse
    </div>
</div>

<!-- Include the Edit Pet Modal -->
@include('modals.edit-pet')

@include('modals.view-pet') <!-- Add this line -->

<script>
function initializePetsPage() {
    // Search and filter functionality
    const searchPet = document.getElementById('searchPet');
    const speciesFilters = document.querySelectorAll('.species-filter');
    const petCards = document.querySelectorAll('.col-12.col-md-6.col-lg-4.col-xl-3'); // Updated selector

    if (searchPet) {
        searchPet.addEventListener('input', filterPets);
    }

    speciesFilters.forEach(button => {
        button.addEventListener('click', () => {
            speciesFilters.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
            filterPets();
        });
    });

    function filterPets() {
        const searchTerm = searchPet.value.toLowerCase();
        const activeSpecies = document.querySelector('.species-filter.active').dataset.species;

        petCards.forEach(card => {
            // Get pet name for search filtering
            const petName = card.querySelector('h3').textContent.toLowerCase();
            
            // Get the species text from the species badge
            // This targets the specific badge in the top-right of the card that contains species
            const speciesElement = card.querySelector('.tw-absolute.tw-top-3.tw-right-3 span');
            let petSpecies = '';
            
            if (speciesElement) {
                // Extract just the species text and trim whitespace
                petSpecies = speciesElement.textContent.replace(/\s+/g, ' ').trim();
            }
            
            const matchesSearch = petName.includes(searchTerm);
            const matchesSpecies = activeSpecies === 'all' || petSpecies === activeSpecies;
            
            // Use the parent of the card for proper grid display handling
            if (matchesSearch && matchesSpecies) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    }
}

// Global functions for pet actions
window.editPet = function(petId) {
    fetch(`/pets/${petId}/edit`)
        .then(response => response.json())
        .then(pet => {
            document.getElementById('editPet-modal').querySelector('form').action = `/pets/${petId}`;
            document.getElementById('name').value = pet.name;
            document.getElementById('species').value = pet.species;
            document.getElementById('petType').value = pet.petType;
            document.getElementById('gender').value = pet.gender;
            document.getElementById('birthDate').value = pet.birthDate;
            document.getElementById('weight').value = pet.weight;
            document.getElementById('medicalHistory').value = pet.medicalHistory;
            document.getElementById('petNotes').value = pet.petNotes;
            document.getElementById('isVaccinated').checked = pet.isVaccinated;
            document.getElementById('lastVaccinationDate').value = pet.lastVaccinationDate;
            document.getElementById('preview-image').src = `/storage/${pet.petImage}`;
            document.getElementById('preview-area').classList.remove('tw-hidden');
            document.getElementById('upload-area').classList.add('tw-hidden');
            document.getElementById('editPet-modal').classList.remove('tw-hidden');
        })
        .catch(error => console.error('Error fetching pet data:', error));
}



// Add this function to close the view pet modal
window.closeViewPetModal = function() {
    document.getElementById('viewPet-modal').classList.add('tw-hidden');
}

window.toggleDropdown = function() {
    const dropdown = document.getElementById('dropdown');
    dropdown.classList.toggle('tw-hidden');
}

// Initialize on direct page load
document.addEventListener('DOMContentLoaded', initializePetsPage);

// Initialize when content is dynamically loaded
document.addEventListener('contentChanged', initializePetsPage);

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('dropdown');
    const profileImg = document.querySelector('[onclick="toggleDropdown()"]');
    if (dropdown && !dropdown.contains(event.target) && event.target !== profileImg) {
        dropdown.classList.add('tw-hidden');
    }
});
</script>
@endsection