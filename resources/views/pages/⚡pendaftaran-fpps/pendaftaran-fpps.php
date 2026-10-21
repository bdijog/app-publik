<?php

use App\Enums\CustomerType;
use App\Enums\PaymentStatus;
use App\Enums\RegistrationChannel;
use App\Enums\SampleStatus;
use App\Enums\TestRequestStatus;
use App\Models\Customer;
use App\Models\Sample;
use App\Models\SampleType;
use App\Models\ServiceGroup;
use App\Models\ServicePackage;
use App\Models\TestParameter;
use App\Models\TestRequest;
use App\Models\TestRequestParameter;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

new class extends Component
{
    public int $currentStep = 1;

    // Step 1: Profil Pemohon
    public string $applicant_type = 'individual';

    public string $customer_name = '';

    public string $organization_name = '';

    public string $customer_phone = '';

    public string $customer_email = '';

    public string $customer_address = '';

    // Step 2: Layanan & Parameter
    public ?int $service_group_id = null;

    public ?int $service_package_id = null;

    /** @var array<int> */
    public array $selected_parameters = [];

    // Step 3: Sampel Fisik
    public string $sample_name = '';

    public ?int $sample_type_id = null;

    public string $sampling_location = '';

    public string $sampling_date = '';

    public string $sampling_time = '09:00';

    public float $sample_quantity = 1.0;

    public string $sample_unit = 'wadah';

    public string $sample_notes = '';

    // Step 4: Penyerahan & Persetujuan
    public string $delivery_method = 'direct';

    public string $purpose = 'Pemeriksaan Baku Mutu / Kepentingan Faskes';

    public bool $agree_terms = false;

    // Step 5: Hasil Sukses
    public ?int $createdRequestId = null;

    public string $createdFppsNumber = '';

    public float $createdTotalAmount = 0.0;

    public function mount(): void
    {
        $this->sampling_date = now()->format('Y-m-d');

        if (auth('customer')->check()) {
            /** @var Customer $customer */
            $customer = auth('customer')->user();
            $this->customer_name = $customer->name ?? '';
            $this->organization_name = $customer->organization_name ?? '';
            $this->customer_email = $customer->email ?? '';
            $this->customer_phone = $customer->phone ?? '';
            $this->customer_address = $customer->address ?? '';
            $this->applicant_type = $customer->customer_type instanceof CustomerType
                ? $customer->customer_type->value
                : ($customer->customer_type ?? 'individual');
        }

        if (request()->has('group') || request()->has('category')) {
            $catCode = request()->get('group') ?? request()->get('category');
            $grp = is_numeric($catCode)
                ? ServiceGroup::where('id', (int) $catCode)->orWhere('code', (string) $catCode)->first()
                : ServiceGroup::where('code', $catCode)->first();
            if ($grp) {
                $this->service_group_id = $grp->id;
            }
        }

        if (request()->has('params')) {
            $raw = request()->get('params');
            $this->selected_parameters = array_filter(array_map('intval', explode(',', (string) $raw)));
            if (! empty($this->selected_parameters) && ! $this->service_group_id) {
                $firstParam = TestParameter::find(reset($this->selected_parameters));
                if ($firstParam) {
                    $this->service_group_id = $firstParam->service_group_id;
                }
            }
        }

        if (! $this->service_group_id) {
            $firstGrp = ServiceGroup::first();
            $this->service_group_id = $firstGrp?->id;
        }
    }

    public function nextStep(): void
    {
        if ($this->currentStep === 1) {
            $this->validate([
                'applicant_type' => 'required|in:individual,organization,health_facility',
                'customer_name' => 'required|string|min:3|max:255',
                'customer_phone' => 'required|string|min:8|max:20',
                'customer_email' => 'required|email|max:255',
                'customer_address' => 'required|string|min:5|max:500',
                'organization_name' => $this->applicant_type !== 'individual' ? 'required|string|min:3|max:255' : 'nullable|string|max:255',
            ]);
            $this->currentStep = 2;
        } elseif ($this->currentStep === 2) {
            $this->validate([
                'service_group_id' => 'required|exists:service_groups,id',
                'selected_parameters' => 'required|array|min:1',
            ], [
                'selected_parameters.min' => 'Pilih minimal satu parameter atau paket pengujian untuk melanjutkan.',
            ]);
            $this->currentStep = 3;
        } elseif ($this->currentStep === 3) {
            $this->validate([
                'sample_name' => 'required|string|min:3|max:255',
                'sampling_location' => 'required|string|min:3|max:255',
                'sampling_date' => 'required|date',
            ]);
            $this->currentStep = 4;
        }
    }

    public function prevStep(): void
    {
        if ($this->currentStep > 1 && $this->currentStep < 5) {
            $this->currentStep--;
        }
    }

    public function toggleParameter(int $id): void
    {
        if (in_array($id, $this->selected_parameters, true)) {
            $this->selected_parameters = array_values(array_diff($this->selected_parameters, [$id]));
        } else {
            $this->selected_parameters[] = $id;
        }
    }

    public function selectPackage(?int $packageId): void
    {
        $this->service_package_id = $packageId;
        if ($packageId) {
            $pkg = ServicePackage::with('parameters')->find($packageId);
            if ($pkg) {
                $ids = $pkg->parameters->pluck('id')->toArray();
                $this->selected_parameters = array_values(array_unique(array_merge($this->selected_parameters, $ids)));
            }
        }
    }

    public function submitRegistration(): void
    {
        $this->validate([
            'agree_terms' => 'accepted',
            'delivery_method' => 'required|in:direct,courier',
        ], [
            'agree_terms.accepted' => 'Anda wajib menyetujui pernyataan keabsahan dan integritas sampel.',
        ]);

        DB::transaction(function () {
            // 1. Resolve or create Customer
            if (auth('customer')->check()) {
                $customer = auth('customer')->user();
            } else {
                $customer = Customer::where('email', $this->customer_email)->first();
                if (! $customer) {
                    $customer = Customer::create([
                        'name' => $this->customer_name,
                        'organization_name' => $this->applicant_type !== 'individual' ? $this->organization_name : null,
                        'customer_type' => $this->applicant_type,
                        'email' => $this->customer_email,
                        'phone' => $this->customer_phone,
                        'address' => $this->customer_address,
                        'password' => bcrypt('Password123!'), // temporary or auto-registered
                    ]);
                } else {
                    $customer->update([
                        'phone' => $this->customer_phone,
                        'address' => $this->customer_address,
                    ]);
                }
            }

            // 2. Calculate Total Amount
            $params = TestParameter::with('tariffs')->whereIn('id', $this->selected_parameters)->get();
            $total = (float) $params->sum(function ($p) {
                return (float) ($p->tariffs->first()?->amount ?? 0);
            });

            // 3. Create TestRequest
            $collectedDateTime = $this->sampling_date.' '.($this->sampling_time ?: '09:00').':00';

            $testRequest = TestRequest::create([
                'customer_id' => $customer->id,
                'service_group_id' => $this->service_group_id,
                'service_package_id' => $this->service_package_id,
                'purpose' => $this->purpose,
                'channel' => RegistrationChannel::Online,
                'sampling_location' => $this->sampling_location,
                'sampling_date' => $this->sampling_date,
                'notes' => 'Metode kirim: '.($this->delivery_method === 'direct' ? 'Loket Balai di Ambon' : 'Kurir Rantai Dingin').'. '.$this->sample_notes,
                'total_amount' => $total,
                'payment_status' => PaymentStatus::Unpaid,
                'status' => TestRequestStatus::Submitted,
                'registered_at' => now(),
            ]);

            // 4. Create Sample record
            $sampleTypeId = $this->sample_type_id;
            if (! $sampleTypeId) {
                $sampleTypeId = SampleType::where('service_group_id', $this->service_group_id)->value('id')
                    ?? SampleType::first()?->id
                    ?? SampleType::create([
                        'service_group_id' => $this->service_group_id,
                        'name' => 'Sampel Uji Umum',
                    ])->id;
            }
            if (! $sampleTypeId) {
                $fallbackType = SampleType::create([
                    'service_group_id' => $this->service_group_id,
                    'name' => 'Sampel Standar',
                ]);
                $sampleTypeId = $fallbackType->id;
            }

            Sample::create([
                'test_request_id' => $testRequest->id,
                'sample_type_id' => $sampleTypeId,
                'sample_name' => $this->sample_name,
                'quantity' => $this->sample_quantity,
                'unit' => $this->sample_unit,
                'collected_at' => $collectedDateTime,
                'status' => SampleStatus::Pending,
            ]);

            // 5. Create TestRequestParameters
            foreach ($params as $param) {
                $price = (float) ($param->tariffs->first()?->amount ?? 0);
                TestRequestParameter::create([
                    'test_request_id' => $testRequest->id,
                    'test_parameter_id' => $param->id,
                    'price' => $price,
                ]);
            }

            $this->createdRequestId = $testRequest->id;
            $this->createdFppsNumber = $testRequest->fpps_number;
            $this->createdTotalAmount = $total;
            $this->currentStep = 5;
        });
    }

    public function render(): mixed
    {
        $serviceGroups = ServiceGroup::query()->orderBy('name')->get();

        $currentGroup = $this->service_group_id ? ServiceGroup::find($this->service_group_id) : $serviceGroups->first();

        $groupPackages = $this->service_group_id
            ? ServicePackage::where('service_group_id', $this->service_group_id)->with('parameters.tariffs')->get()
            : collect();

        $groupParameters = $this->service_group_id
            ? TestParameter::active()->where('service_group_id', $this->service_group_id)->with('tariffs')->get()
            : collect();

        $sampleTypes = $this->service_group_id
            ? SampleType::where('service_group_id', $this->service_group_id)->get()
            : SampleType::take(10)->get();

        $selectedParamModels = TestParameter::with('tariffs')->whereIn('id', $this->selected_parameters)->get();
        $totalEstimatedCost = $selectedParamModels->sum(function ($p) {
            return (float) ($p->tariffs->first()?->amount ?? 0);
        });

        return view('pages::⚡pendaftaran-fpps.pendaftaran-fpps', [
            'serviceGroups' => $serviceGroups,
            'currentGroup' => $currentGroup,
            'groupPackages' => $groupPackages,
            'groupParameters' => $groupParameters,
            'sampleTypes' => $sampleTypes,
            'selectedParamModels' => $selectedParamModels,
            'totalEstimatedCost' => $totalEstimatedCost,
        ]);
    }
};
