<div class="space-y-4">
    @forelse($value as $i => $row)
        <div wire:key="rg-row-{{ $row['_uid'] ?? 'i-'.$i }}"
             class="card border border-accent-content/10 p-4 grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 shadow-lg">
            @php $rowKey = $row['_uid'] ?? $i; @endphp
            @foreach($fields as $fkey)
                @php
                    $meta    = $fieldMeta[$fkey] ?? [];
                    $label   = $meta['label'] ?? $fkey;
                    $extra   = $meta['extra'] ?? [];
                    $type    = $extra['type'] ?? 'text';
                    $choices = $extra['choices'] ?? [];
                @endphp

                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium">{{ $label }}</label>

                    @if($fkey === 'geolocation')

                        <x-input
                            wire:click.debounce.250ms="openMap({{ $i }})"
                            wire:model.defer="value.{{ $i }}.geolocation"
                            placeholder="00.00000 | 00.00000"
                            readonly
                            class="read-only:bg-base-200">

                            <x-slot:append>
                                <x-button
                                    label="{{ __('Pick') }}"
                                    icon="o-map"
                                    spinner
                                    class="join-item btn-primary btn-sm mt-1 rounded-br-2xl rounded-tr-2xl"
                                    wire:click="openMap({{ $i }})"/>
                            </x-slot:append>
                        </x-input>

                                            @elseif($type === 'select' && $fkey === 'country')

                                                <x-choices-offline

                                                    wire:key="c-{{$i.$fkey}}"

                                                    wire:model="value.{{ $i }}.{{ $fkey }}"
                                                    :options="$countryOptions"
                                                    option-label="label"
                                                    option-value="value"
                                                    placeholder="{{ __('Select a country...') }}"
                                                    searchable
                                                    clearable
                                                    single />


{{--                                            @elseif($type === 'select' && $fkey === 'nace')--}}

{{--                                                <x-choices-offline--}}


{{--                                                    wire:model="value.{{ $i }}.{{ $fkey }}"--}}
{{--                                                    :options="$naceOptions"--}}
{{--                                                    option-label="label"--}}
{{--                                                    option-value="value"--}}
{{--                                                    placeholder="{{__('Select NACE code')}}"--}}
{{--                                                    searchable--}}
{{--                                                    clearable--}}
{{--                                                    single />--}}

                    @elseif($type === 'select')
                        <select class="select select-bordered w-full"
                                wire:model.defer="value.{{ $i }}.{{ $fkey }}"
                                aria-label="{{ $label }}">
                            <option value="">— Select —</option>
                            @foreach($choices as $opt)
                                <option value="{{ $opt['value'] ?? '' }}">
                                    {{ $opt['label'] ?? ($opt['value'] ?? '') }}
                                </option>
                            @endforeach
                        </select>

                    @elseif($type === 'textarea')
                        <textarea class="textarea textarea-bordered w-full"
                                  rows="3"
                                  wire:model.defer="value.{{ $i }}.{{ $fkey }}"
                                  placeholder="{{ $extra['placeholder'] ?? '' }}"
                                  aria-label="{{ $label }}"></textarea>

                    @else
                        <input type="text"
                               class="input input-bordered w-full"
                               wire:model.defer="value.{{ $i }}.{{ $fkey }}"
                               placeholder="{{ $extra['placeholder'] ?? '' }}"
                               aria-label="{{ $label }}"/>
                    @endif
                </div>

            @endforeach

            @if($companyType == 'consolidated')
                <div class="md:col-span-2 flex justify-end">
                    <x-button label="Remove" icon="o-trash" spinner
                              class="btn btn-error btn-outline btn-sm"
                              wire:click="removeRow({{ $i }})"/>
                </div>
            @endif
        </div>

    @empty
        <div role="alert" class="alert mt-3 mb-3">
            <x-icon name="o-exclamation-triangle" class="text-primary"/>
            <span>{{ __('No company row added') }}</span>
        </div>
    @endforelse

    @if($companyType == 'consolidated')
        <x-button label="{{ __('Add company') }}" icon="o-plus"
                  class="btn btn-outline btn-sm"
                  wire:click.debounce.200ms="addRow" spinner/>
    @endif


        <x-modal wire:model="showMap" :subtitle="__('Select on map')">
            <div
                x-data="{
      open:  @entangle('showMap').live,
      domId: @entangle('mapDomId'),
      compId: '{{$this->id()}}',
      picked: @entangle('mapValue'),
    }"
                x-init="
      const init = () => {
        if (open && domId) {
          setTimeout(() => {
            window.dispatchEvent(new CustomEvent('leaflet:init', {
              detail: { id: domId, coords: @js($mapValue), componentId: compId }
            }));
          }, 120);
        }
      };
      $watch('open', init);
      $watch('domId', init);
    "
                class="space-y-4"
            >
                {{-- Toolbar --}}
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-base-content/60 hidden sm:inline">{{ __('Pick a point or use shortcuts') }}</span>
                    </div>

                    <div class="flex items-center gap-2">
                        <button type="button"
                                class="btn btn-ghost btn-sm border border-dashed hover:border-primary/60"
                                title="{{ __('Use my location') }}"
                                @click="window.dispatchEvent(new CustomEvent('leaflet:locate', { detail: { id: domId, componentId: compId } }))">
                            <span class="mr-1">📍</span>{{ __('Locate me') }}
                        </button>

                        <div class="hidden sm:flex items-center gap-1">
                            <button type="button" class="btn btn-outline btn-sm"
                                    title="Switzerland"
                                    @click="window.dispatchEvent(new CustomEvent('leaflet:center', { detail: { id: domId, country: 'CH', componentId: compId } }))">CH</button>
                            <button type="button" class="btn btn-outline btn-sm"
                                    title="Finland"
                                    @click="window.dispatchEvent(new CustomEvent('leaflet:center', { detail: { id: domId, country: 'FI', componentId: compId } }))">FI</button>
                            <button type="button" class="btn btn-outline btn-sm"
                                    title="United States"
                                    @click="window.dispatchEvent(new CustomEvent('leaflet:center', { detail: { id: domId, country: 'US', componentId: compId } }))">US</button>
                            <button type="button" class="btn btn-outline btn-sm"
                                    title="Russia"
                                    @click="window.dispatchEvent(new CustomEvent('leaflet:center', { detail: { id: domId, country: 'RU', componentId: compId } }))">RU</button>
                        </div>
                    </div>
                </div>

                {{-- Map card --}}
                <div class="rounded-2xl border border-base-300 bg-base-100 shadow-sm overflow-hidden">
                    <div class="p-2 border-b border-base-200 bg-base-200/40">
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-base-content/70">{{ __('Map') }}</span>
                            <span class="text-[10px] text-base-content/50">{{ __('Drag & click to set a marker') }}</span>
                        </div>
                    </div>

                    <div id="{{ $mapDomId ?? 'map-pending' }}"
                         wire:ignore
                         class="h-[420px] w-full ring-1 ring-base-200/60"></div>

                    <div class="px-3 py-2 bg-base-200/30 border-t border-base-200">
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="text-xs text-base-content/60">{{ __('Selected') }}:</span>
                                <span class="badge badge-outline badge-sm whitespace-nowrap"
                                      x-text="picked || '—'"></span>
                            </div>

                            <div class="flex items-center gap-2">
                                <button type="button"
                                        class="btn btn-ghost btn-xs"
                                        @click="picked = null; $wire.set('mapValue', null, true)">
                                    {{ __('Reset') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-end">
{{--                    <x-button class="btn-ghost" :label="__('Cancel')" wire:click="$set('showMap', false)"/>--}}

                    <x-button class="btn-primary btn-xs"

                              :label="__('Use this')"
                              wire:click="$set('showMap', false)"/>

                </div>
            </div>
        </x-modal>



    @once
            @assets
            <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
            <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
            @endassets
        @endonce

        @push('scripts')
            <script>
                document.addEventListener('livewire:initialized', () => {
                    const registry = {}; // { id: { map, marker } }




                    window.addEventListener('leaflet:init', (e) => {
                        const { id, coords, componentId } = e.detail || {};
                        const el = document.getElementById(id);
                        if (!el) return;

                        // اگر قبلاً نقشه‌ای ثبت شده ولی container عوض شده → پاکسازی کامل
                        if (registry[id]?.map && registry[id].map._container !== el) {
                            try { registry[id].map.remove(); } catch(_) {}
                            delete registry[id];
                        }


                        window.addEventListener('leaflet:locate', (e) => {
                            const { id, componentId } = e.detail || {};
                            const rec = registry[id];
                            if (!rec || !rec.map) return;

                            if (!('geolocation' in navigator)) {
                                alert('⚠️ Your browser does not support geolocation.');
                                return;
                            }

                            navigator.geolocation.getCurrentPosition(
                                (pos) => {
                                    const lat = pos.coords.latitude;
                                    const lng = pos.coords.longitude;
                                    const acc = pos.coords.accuracy; // in meters

                                    // مرکز و مارکر
                                    const latlng = [lat, lng];
                                    rec.map.setView(latlng, 12);

                                    if (rec.marker) rec.marker.setLatLng(latlng);
                                    else rec.marker = L.marker(latlng).addTo(rec.map);

                                    // حلقه دقت (accuracy circle)
                                    if (rec.accuracy) {
                                        try { rec.map.removeLayer(rec.accuracy); } catch(_) {}
                                        rec.accuracy = null;
                                    }
                                    rec.accuracy = L.circle(latlng, {
                                        radius: acc,
                                        color: '#2563eb',
                                        fillColor: '#3b82f6',
                                        fillOpacity: 0.2
                                    }).addTo(rec.map);

                                    // مقدار را به Livewire بده (defer = true)
                                    const val = `${lat.toFixed(6)} | ${lng.toFixed(6)}`;
                                    const comp = window.Livewire.find(componentId);
                                    if (comp) comp.set('mapValue', val, true);
                                },
                                (err) => {
                                    // خطاهای رایج: PERMISSION_DENIED, POSITION_UNAVAILABLE, TIMEOUT
                                    alert('⚠️ Unable to access your location.');
                                },
                                { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
                            );
                        });

                        const PRESETS = {
                            CH: [46.8182, 8.2275],   // Switzerland
                            FI: [61.9241, 25.7482],  // Finland
                            US: [37.0902, -95.7129], // United States
                            RU: [61.5240, 105.3188], // Russia
                        };
                        const ZOOMS = { CH: 6, FI: 5, US: 4, RU: 3 };

                        window.addEventListener('leaflet:center', (e) => {
                            const { id, country, componentId } = e.detail || {};
                            const rec = registry[id];
                            if (!rec || !rec.map) return;

                            const center = PRESETS[country];
                            if (!center) return;

                            const zoom = ZOOMS[country] ?? 5;
                            rec.map.setView(center, zoom);

                            // مارکر را روی مرکز preset بگذاریم/جابجا کنیم
                            if (rec.marker) rec.marker.setLatLng(center);
                            else rec.marker = L.marker(center).addTo(rec.map);

                            // مقدار ورودی را هم به‌روزرسانی کن (defer=true)
                            const val = `${center[0].toFixed(6)} | ${center[1].toFixed(6)}`;
                            const comp = window.Livewire.find(componentId);
                            if (comp) comp.set('mapValue', val, true);
                        });



                        // اگر هنوز map نداریم → بساز
                        if (!registry[id]) {
                            const map = L.map(el);
                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {maxZoom: 19}).addTo(map);

                            let center = [35.6892, 51.3890]; // Tehran-ish
                            let zoom = 6;
                            let marker = null;

                            // اگر coords اولیه داریم، مارکر بگذار و مرکز بده
                            if (typeof coords === 'string' && coords.includes('|')) {
                                const [latS, lngS] = coords.split('|').map(s => parseFloat(s.trim()));
                                if (!Number.isNaN(latS) && !Number.isNaN(lngS)) {
                                    center = [latS, lngS];
                                    zoom = 12;
                                    marker = L.marker(center, { draggable: true }).addTo(map);
                                }
                            }

                            registry[id] = { map, marker };

                            // هندل کلیک روی نقشه: مارکر بگذار/جابجا کن و مقدار Livewire را ست کن
                            const setPicked = (latlng) => {
                                const lat = latlng.lat.toFixed(6);
                                const lng = latlng.lng.toFixed(6);
                                const picked = `${lat} | ${lng}`;

                                // پیش‌نمایش (اختیاری)
                                const prev = document.getElementById(id + '-preview') || document.getElementById('map-container-preview');
                                if (prev) prev.textContent = picked;

                                // ست به Livewire property mapValue (defer = true)
                                const comp = window.Livewire.find(componentId);
                                if (comp) comp.set('mapValue', picked, true);
                            };

                            map.on('click', (ev) => {
                                if (registry[id].marker) registry[id].marker.setLatLng(ev.latlng);
                                else registry[id].marker = L.marker(ev.latlng, { draggable: true }).addTo(map);
                                setPicked(ev.latlng);

                                // درگ مارکر هم آپدیت کند
                                registry[id].marker.off('dragend'); // duplicate handler نشود
                                registry[id].marker.on('dragend', (e) => setPicked(e.target.getLatLng()));
                            });

                            // اگر مارکر اولیه داشتیم، هندل درگش را هم ست کن
                            if (marker) {
                                marker.on('dragend', (e) => setPicked(e.target.getLatLng()));
                            }

                            setTimeout(() => { map.invalidateSize(); map.setView(center, zoom); }, 120);
                            return;
                        }

                        // اگر map قبلاً هست: فقط refresh و اگر coords جدید داده شده، مرکز/مارکر را ست کن
                        const { map, marker } = registry[id];

                        if (typeof coords === 'string' && coords.includes('|')) {
                            const [latS, lngS] = coords.split('|').map(s => parseFloat(s.trim()));
                            if (!Number.isNaN(latS) && !Number.isNaN(lngS)) {
                                const latlng = L.latLng(latS, lngS);
                                map.setView(latlng, 12);
                                if (marker) marker.setLatLng(latlng);
                                else registry[id].marker = L.marker(latlng, { draggable: true }).addTo(map);
                            }
                        }

                        setTimeout(() => map.invalidateSize(), 120);
                    });

                    // اگر container حذف شد (مثلاً با Back)، رجیستری را پاک کن
                    Livewire.hook('element.removed', (el) => {
                        const id = el?.id;
                        if (!id) return;
                        if (registry[id]?.map) {
                            try { registry[id].map.remove(); } catch(_) {}
                            delete registry[id];
                        }
                    });

                    // ایمنی بعد از هر diff
                    Livewire.hook('message.processed', () => {
                        Object.values(registry).forEach(({ map }) => {
                            try { map.invalidateSize(); } catch(_) {}
                        });
                    });
                });
            </script>
        @endpush



</div>


