<?php

use App\Models\ServiceGroup;
use App\Models\TestParameter;
use Livewire\Component;

new class extends Component
{
    public string $fppsQuery = '';

    public string $parameterQuery = '';

    public function searchFpps(): mixed
    {
        $fpps = trim($this->fppsQuery);
        if (! empty($fpps)) {
            return redirect()->route('lacak', ['fpps' => $fpps]);
        }

        return redirect()->route('lacak');
    }

    public function searchParameter(): mixed
    {
        $param = trim($this->parameterQuery);
        if (! empty($param)) {
            return redirect()->route('katalog', ['search' => $param]);
        }

        return redirect()->route('katalog');
    }

    public function render(): mixed
    {
        $serviceGroups = ServiceGroup::query()->orderBy('code')->take(6)->get();
        $totalParameters = TestParameter::query()->count();
        $totalGroups = ServiceGroup::query()->count();

        return view('pages::⚡beranda.beranda', [
            'serviceGroups' => $serviceGroups,
            'totalParameters' => $totalParameters,
            'totalGroups' => $totalGroups,
        ]);
    }
};
