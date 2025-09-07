<div class="grid grid-cols-[3.5rem_1fr] gap-6">


    @include('livewire.user-dashboard.wizard.partials.steps-rail', [
        'current' => $this->current,

        'visible' => $this->visible,

    ])


    {{-- RIGHT: Question Content --}}

    <section class="max-w-5xl mx-auto px-4 lg:px-2 py-6 bg-base-100">

        @include('livewire.user-dashboard.wizard.partials.top-bar')

        @include('livewire.user-dashboard.wizard.partials.q-bar', [
            'index'    => $this->index,
            'progress' => $this->progress,
        ])

        @include('livewire.user-dashboard.wizard.partials.levels', ['active' => $this->index])

        <!-- Questionnaire -->




        <livewire:user-dashboard.wizard.question-block
            :moduleChoice="$this->moduleChoice"
            :companyType="$this->companyType"
            :q="$questions[$this->currentKey] ?? []"
            :total="$this->total"
            wire:model="answers.{{ $this->currentKey }}"
            wire:key="q-{{ $this->currentKey }}"
        />

        <!-- /Questionnaire -->


        @include('livewire.user-dashboard.wizard.partials.actions')


        @include('livewire.user-dashboard.wizard.partials.footnote', [
     'q' => $questions[$this->currentKey] ?? [],
     'currentKey' => $this->currentKey,
 ])
    </section>



</div>
