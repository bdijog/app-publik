<?php

use App\Models\TestReport;
use Livewire\Component;

new class extends Component
{
    public ?string $number = null;

    public function mount(?string $number = null): void
    {
        $this->number = $number;
    }

    public function render(): mixed
    {
        $reportQuery = TestReport::with([
            'testRequest.customer',
            'testRequest.serviceGroup',
            'testRequest.samples.sampleType',
            'verifier',
            'validator',
        ]);

        if (! empty($this->number)) {
            $report = $reportQuery->where('report_number', $this->number)
                ->orWhereHas('testRequest', function ($q) {
                    $q->where('fpps_number', $this->number)
                        ->orWhere('request_number', $this->number);
                })
                ->first();
        } else {
            // Default to latest validated report for preview
            $report = $reportQuery->latest()->first();
        }

        return view('pages::⚡scan-lhu.scan-lhu', [
            'report' => $report,
        ]);
    }
};
