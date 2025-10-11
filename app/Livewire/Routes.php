<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Route as RouteModel;
use Livewire\WithPagination;

class Routes extends Component
{
    use WithPagination;

    public $searchTerm = '';
    public $typeFilter = 'all';
    public $cargoFilter = false;
    public $scopeFilter = 'all';
    public $eventId = null;
    public $activeFilters = [];

    protected $queryString = [
        'typeFilter' => ['except' => 'all'],
        'cargoFilter' => ['except' => false],
        'scopeFilter' => ['except' => 'all'],
        'searchTerm' => ['except' => '']
    ];

    public function mount($eventId = null)
    {
        $this->eventId = $eventId;
        $this->updateActiveFilters();
    }

    // Método para filtrar por tipo (Arrival/Departure)
    public function filterByType($type)
    {
        $this->resetPage();
        $this->typeFilter = $type;
        $this->updateActiveFilters();
    }

    // Método para filtrar por alcance (Nacional/Internacional)
    public function filterByScope($scope)
    {
        $this->resetPage();
        $this->scopeFilter = $scope;
        $this->updateActiveFilters();
    }

    // Alternar filtro de carga
    public function toggleCargo()
    {
        $this->resetPage();
        $this->cargoFilter = !$this->cargoFilter;
        $this->updateActiveFilters();
    }

    // Buscar por término
    public function search()
    {
        $this->resetPage();
    }

    // Limpiar todos los filtros
    public function clearFilters()
    {
        $this->resetPage();
        $this->typeFilter = 'all';
        $this->cargoFilter = false;
        $this->scopeFilter = 'all';
        $this->searchTerm = '';
        $this->updateActiveFilters();
    }

    private function updateActiveFilters()
    {
        $this->activeFilters = [];

        if ($this->typeFilter !== 'all') {
            $this->activeFilters[] = ucfirst($this->typeFilter);
        }

        if ($this->cargoFilter) {
            $this->activeFilters[] = 'Cargo';
        }

        if ($this->scopeFilter !== 'all') {
            $this->activeFilters[] = ucfirst($this->scopeFilter);
        }
    }

    // Este es el método crítico que debe retornar la vista
    public function render()
    {
        // Iniciar la consulta
        $query = RouteModel::query();

        // Filtrar por evento si existe
        if ($this->eventId) {
            $query->where('event_id', $this->eventId);
        }

        // Filtrar por tipo (Arrival/Departure)
        if ($this->typeFilter !== 'all') {
            $query->where('type', $this->typeFilter);
        }

        // Filtrar por carga
        if ($this->cargoFilter) {
            $query->where('is_cargo', true);
        }

        // Filtrar por alcance (Nacional/Internacional)
        if ($this->scopeFilter !== 'all') {
            $query->where('is_international', $this->scopeFilter === 'international');
        }

        // Aplicar búsqueda
        if (!empty($this->searchTerm)) {
            $query->where(function ($q) {
                $q->where('flight_number', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('airline', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('origin', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('destination', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('name_airport_origin', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('name_airport_destination', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('iato_code_origin', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('iato_code_destination', 'like', '%' . $this->searchTerm . '%');
            });
        }

        // Ordenar por hora de origen
        $query->orderBy('hourOrigin', 'asc');

        // Retornar la vista con los resultados paginados
        return view('livewire.routes', [
            'routes' => $query->paginate(10)
        ]);
    }
}
