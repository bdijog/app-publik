<?php

use App\Models\ServiceGroup;
use App\Models\ServicePackage;
use App\Models\TestParameter;
use Livewire\Attributes\Url;
use Livewire\Component;

new class extends Component
{
    #[Url]
    public string $search = '';

    #[Url]
    public string $category = 'semua';

    /** @var array<int> */
    public array $selectedParameters = [];

    public function mount(): void
    {
        // If query parameters passed selected ids
        if (request()->has('params')) {
            $raw = request()->get('params');
            $this->selectedParameters = array_filter(array_map('intval', explode(',', (string) $raw)));
        }
    }

    public function selectCategory(string $cat): void
    {
        $this->category = $cat;
    }

    public function toggleParameter(int $parameterId): void
    {
        if (in_array($parameterId, $this->selectedParameters, true)) {
            $this->selectedParameters = array_values(array_diff($this->selectedParameters, [$parameterId]));
        } else {
            $this->selectedParameters[] = $parameterId;
        }
    }

    public function togglePackage(int $packageId): void
    {
        $package = ServicePackage::with('parameters')->find($packageId);
        if (! $package) {
            return;
        }

        $paramIds = $package->parameters->pluck('id')->toArray();
        $allSelected = count(array_intersect($paramIds, $this->selectedParameters)) === count($paramIds);

        if ($allSelected) {
            $this->selectedParameters = array_values(array_diff($this->selectedParameters, $paramIds));
        } else {
            $this->selectedParameters = array_values(array_unique(array_merge($this->selectedParameters, $paramIds)));
        }
    }

    public function resetSelection(): void
    {
        $this->selectedParameters = [];
    }

    public function proceedToRegistration(): mixed
    {
        if (empty($this->selectedParameters)) {
            return redirect()->route('pendaftaran');
        }

        return redirect()->route('pendaftaran', [
            'params' => implode(',', $this->selectedParameters),
            'category' => $this->category !== 'semua' ? $this->category : '',
        ]);
    }

    public function render(): mixed
    {
        $serviceGroups = ServiceGroup::query()->orderBy('name')->get();

        $parametersQuery = TestParameter::query()
            ->active()
            ->with(['serviceGroup', 'tariffs' => function ($q) {
                $q->latest('effective_from');
            }]);

        if ($this->category !== 'semua') {
            $category = $this->category;
            $parametersQuery->whereHas('serviceGroup', function ($q) use ($category) {
                if (is_numeric($category)) {
                    $q->where(function ($sub) use ($category) {
                        $sub->where('id', (int) $category)
                            ->orWhere('code', (string) $category);
                    });
                } else {
                    $q->where('code', $category);
                }
            });
        }

        if (! empty(trim($this->search))) {
            $term = '%'.trim($this->search).'%';
            $parametersQuery->where(function ($q) use ($term) {
                $q->whereLike('name', $term)
                    ->orWhereLike('method', $term)
                    ->orWhereHas('serviceGroup', function ($sub) use ($term) {
                        $sub->whereLike('name', $term);
                    });
            });
        }

        $parameters = $parametersQuery->orderBy('name')->paginate(18);

        // Packages query
        $packagesQuery = ServicePackage::query()
            ->with(['serviceGroup', 'parameters.tariffs']);

        if ($this->category !== 'semua') {
            $category = $this->category;
            $packagesQuery->whereHas('serviceGroup', function ($q) use ($category) {
                if (is_numeric($category)) {
                    $q->where(function ($sub) use ($category) {
                        $sub->where('id', (int) $category)
                            ->orWhere('code', (string) $category);
                    });
                } else {
                    $q->where('code', $category);
                }
            });
        }

        $packages = $packagesQuery->take(6)->get();

        // Calculate selected parameters total amount
        $selectedParamsModels = TestParameter::with('tariffs')->whereIn('id', $this->selectedParameters)->get();
        $totalEstimatedCost = $selectedParamsModels->sum(function ($param) {
            return (float) ($param->tariffs->first()?->amount ?? 0);
        });

        return view('pages::⚡katalog-tarif.katalog-tarif', [
            'serviceGroups' => $serviceGroups,
            'parameters' => $parameters,
            'packages' => $packages,
            'totalEstimatedCost' => $totalEstimatedCost,
            'selectedCount' => count($this->selectedParameters),
        ]);
    }
};
