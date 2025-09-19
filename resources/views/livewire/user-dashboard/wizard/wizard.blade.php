{{-- Wizard Layout --}}

<div class="wizard grid grid-cols-[3.5rem_1fr] gap-6">

    @include('livewire.user-dashboard.wizard.partials.steps-rail', [
        'current' => $this->current,
        'visible' => $this->visible,
    ])

    {{-- RIGHT: Question Content --}}
    <section class="w-full max-w-5xl mx-auto px-4 lg:px-2 py-6 bg-base-100">

        @include('livewire.user-dashboard.wizard.partials.top-bar')

        @include('livewire.user-dashboard.wizard.partials.q-bar', [
            'index'    => $this->index,
            'progress' => $this->progress,
        ])

        {{--        @include('livewire.user-dashboard.wizard.partials.levels', ['active' => $this->index])--}}

        <!-- Questionnaire -->
        <livewire:user-dashboard.wizard.question-block
            :moduleChoice="$this->moduleChoice"
            :companyType="$this->companyType"
            :index="$this->index"
            :q="$questions[$this->currentKey] ?? []"
            :total="$this->total"
            :report-id="$report->id ?? null"
            wire:model="answers.{{ $this->currentKey }}"
            wire:key="q-{{ $this->currentKey }}"
        />


        <!-- /Questionnaire -->

        @include('livewire.user-dashboard.wizard.partials.actions')

        <div class="border border-gray-300 rounded-xl p-4 bg-gray-50 shadow-sm mt-4 mb-4">
            <x-badge class="badge"
                     :value="'Index is: '.$this->index.' and CurrentKey is: '.$currentKey"/>

            <a href="{{ route('panel.questionnaire.reports.show',['report' => $reportId]) }}"
               class="text-blue-600 hover:underline">
                Report
            </a>
        </div>


        @include('livewire.user-dashboard.wizard.partials.footnote', [
            'q' => $questions[$this->currentKey] ?? [],
            'currentKey' => $this->currentKey,
        ])
    </section>
</div>



@push('styles')
    <style>

        .wizard :where(.card) {
            max-width: none !important;
            width: 100% !important;
        }

        .wizard :where(.card > .card-body) {
            max-width: none !important;
            width: 100% !important;
        }

        .wizard :where(label.block) {
            display: block !important;
            width: 100% !important;
        }

        .wizard :where(.alert) {
            width: 100% !important;
        }

        .wizard :where(.prose) {
            max-width: none !important;
        }
    </style>
@endpush
