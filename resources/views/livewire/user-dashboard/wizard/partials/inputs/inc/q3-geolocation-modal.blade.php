{{--<x-modal wire:model="showMapModal" title="Pick location on map" separator>--}}

{{--    @assets--}}
{{--    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"--}}
{{--            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>--}}
{{--    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"--}}
{{--          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>--}}
{{--    @endassets--}}

{{--    @include('livewire.user-dashboard.wizard.partials.inputs.scripts.q-3-repeatable-group-geolocation-script')--}}

{{--    <div class="flex gap-2 justify-start mt-3 mb-2">--}}
{{--        <button--}}
{{--            type="button"--}}
{{--            class="px-3 py-1 rounded bg-blue-600 text-white text-sm btn-sm hover:cursor-pointer"--}}
{{--            onclick="setMapCenter('{{ $mapDomId }}', 'FIN')">--}}
{{--            FIN--}}
{{--        </button>--}}
{{--        <button--}}
{{--            type="button"--}}
{{--            class="px-3 py-1 rounded bg-green-600 text-white text-sm hover:cursor-pointer"--}}
{{--            onclick="setMapCenter('{{ $mapDomId }}', 'USA')">--}}
{{--            USA--}}
{{--        </button>--}}
{{--        <button--}}
{{--            type="button"--}}
{{--            class="px-3 py-1 rounded bg-red-600 text-white text-sm hover:cursor-pointer"--}}
{{--            onclick="setMapCenter('{{ $mapDomId }}', 'CHN')">--}}
{{--            CHN--}}
{{--        </button>--}}

{{--        <button--}}
{{--            type="button"--}}
{{--            class="btn btn-sm btn-outline btn-dash hover:cursor-pointer"--}}
{{--            onclick="locateUser('{{ $mapDomId }}')">--}}
{{--            <div class="inline-grid *:[grid-area:1/1]">--}}
{{--                <div class="status status-success animate-ping"></div>--}}
{{--                <div class="status status-success"></div>--}}
{{--            </div>--}}
{{--            {{__('Locate me')}}--}}
{{--        </button>--}}
{{--        <span wire:loading class="loading loading-spinner text-neutral"></span>--}}

{{--        <div wire:loading class="text-xs text-info mr-20">{{__('Please wait...')}}</div>--}}
{{--    </div>--}}




{{--    <div class="space-y-3">--}}
{{--        <div id="{{ $mapDomId }}" wire:ignore class="w-full h-80 rounded-xl border border-base-300"></div>--}}

{{--        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">--}}
{{--            --}}{{-- Preview --}}
{{--            <div>--}}
{{--            <span id="{{ $mapDomId }}-preview"--}}
{{--                  class="badge badge-outline badge-lg whitespace-nowrap"--}}
{{--                  title="{{ __('Selected coordinates') }}">--}}
{{--                {{ $picked ?? '' }}--}}
{{--            </span>--}}
{{--            </div>--}}

{{--            --}}{{-- Actions --}}
{{--            <div class="flex items-center gap-2">--}}
{{--                <x-button label="Cancel" class="btn btn-ghost"--}}
{{--                          wire:click="$set('showMapModal', false)"/>--}}
{{--                <x-button spinner label="{{ __('Set location') }}" icon="o-check-badge"--}}
{{--                          class="btn btn-primary"--}}
{{--                          onclick="window.commitPicked('{{ $mapDomId }}')"/>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}




{{--</x-modal>--}}

