<?php

use App\Models\TestRequest;
use App\Services\Export\Pdf\TestRequestReceiptPdfGenerator;
use Livewire\Attributes\Url;
use Livewire\Component;

new class extends Component
{
    #[Url]
    public string $fpps = '';

    public string $searchFpps = '';

    public bool $hasSearched = false;

    public function mount(): void
    {
        if (! empty($this->fpps)) {
            $this->searchFpps = $this->fpps;
            $this->hasSearched = true;
        }
    }

    public function track(): void
    {
        $this->fpps = trim($this->searchFpps);
        $this->hasSearched = true;
    }

    public function downloadReceipt(int $requestId): mixed
    {
        $request = TestRequest::find($requestId);
        if ($request) {
            return TestRequestReceiptPdfGenerator::download($request);
        }

        return null;
    }

    public function render(): mixed
    {
        $testRequest = null;

        if (! empty($this->fpps)) {
            $testRequest = TestRequest::with([
                'customer',
                'serviceGroup',
                'samples.sampleType',
                'parameters.testParameter',
                'statusHistories',
                'testReports',
            ])
                ->where('fpps_number', $this->fpps)
                ->orWhere('request_number', $this->fpps)
                ->first();
        }

        return view('pages::⚡lacak-sampel.lacak-sampel', [
            'testRequest' => $testRequest,
        ]);
    }
};
