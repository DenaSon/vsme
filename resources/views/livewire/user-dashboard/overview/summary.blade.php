<x-card class="mt-6 bg-base-100 border border-base-300 shadow-md hover:shadow-xl rounded-2xl transition">
    <x-slot name="title">
        <div class="flex items-center gap-2">
            <x-heroicon-o-clipboard-document-check class="w-5 h-5 text-primary"/>
            <span class="text-sm font-semibold">VSME Overview</span>
        </div>
    </x-slot>

    <div class="px-2 py-3">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-center sm:text-left">

            {{-- Active Questionnaires --}}
            <x-stat
                title="Active Questionnaires"
                :value="$activeQuestionnaires"
                icon="o-document-text"
                color="text-primary"
                tooltip="Number of compliance questionnaires currently active"
                class="mx-auto sm:mx-0"
            />

            {{-- Reports Generated --}}
            <x-stat
                title="Reports Generated"
                :value="$reportsGenerated"
                icon="o-chart-bar"
                color="text-accent"
                tooltip="Total sustainability reports generated"
                class="mx-auto sm:mx-0"
            />

            {{-- Subsidiaries Linked --}}
            <x-stat
                title="Subsidiaries Linked"
                :value="$subsidiariesLinked"
                icon="o-building-office-2"
                color="text-secondary"
                tooltip="Number of subsidiaries linked to this profile"
                class="mx-auto sm:mx-0"
            />

        </div>


    </div>
</x-card>
