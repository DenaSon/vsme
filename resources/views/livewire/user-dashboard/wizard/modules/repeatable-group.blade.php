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

{{--                    @elseif($type === 'select' && $fkey === 'country')--}}
{{--                        <div wire:ignore.self wire:key="{{$i.$fkey}}">--}}
{{--                            <x-choices-offline--}}
{{--                                wire:model="value.{{ $i }}.{{ $fkey }}"--}}
{{--                                :options="$countryOptions"--}}
{{--                                option-label="label"--}}
{{--                                option-value="value"--}}
{{--                                placeholder="{{ __('Select a country...') }}"--}}
{{--                                searchable--}}

{{--                                single/>--}}
{{--                        </div>--}}

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

                    @elseif($type === 'select' && $fkey === 'country')
                        <select class="select select-bordered w-full"
                                wire:model.defer="value.{{ $i }}.{{ $fkey }}"
                                aria-label="{{ $label }}">
                            <option value="">— Select —</option>
                            @foreach($countryOptions as $opt)
                                <option value="{{ $opt['value'] }}">
                                    {{ $opt['label'] }}
                                </option>
                            @endforeach
                        </select>


                    @elseif($type === 'select' && $fkey === 'nace')
                        <select class="select select-bordered w-full"
                                wire:model.defer="value.{{ $i }}.{{ $fkey }}"
                                aria-label="{{ $label }}">
                            <option value="">— Select —</option>
                            @foreach($naceOptions as $opt)
                                <option value="{{ $opt['value'] }}">
                                    {{ $opt['label'] }}
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


        <x-modal wire:model="showMap" wire:key="map-modal"
                 title="{{ __('Select on map') }}">

            <div
                x-data="{
      open: @entangle('showMap').live,
      domId: @entangle('mapDomId').live,
      compId: '{{$this->id()}}'
    }"
                x-init="
      // وقتی open یا domId عوض شدند، بعد از رندر init کن
      $watch('open', v => { if (v && domId) queueMicrotask(() => {
        window.dispatchEvent(new CustomEvent('leaflet:init', { detail: { id: domId, coords: @js($mapValue), componentId: compId }}));
      })});
      $watch('domId', id => { if (open && id) queueMicrotask(() => {
        window.dispatchEvent(new CustomEvent('leaflet:init', { detail: { id, coords: @js($mapValue), componentId: compId }}));
      })});
    "
            >
                <div class="mb-3 flex items-center gap-2">
                    <button type="button" class="btn btn-sm btn-ghost border border-dashed"
                            @click="window.dispatchEvent(new CustomEvent('leaflet:locate', { detail: { id: domId }}))">
                        📍 {{ __('Locate me') }}
                    </button>
                    <button type="button" class="btn btn-sm btn-outline"
                            @click="window.dispatchEvent(new CustomEvent('leaflet:center', { detail: { id: domId, country: 'CH' }}))">CH</button>
                    <button type="button" class="btn btn-sm btn-outline"
                            @click="window.dispatchEvent(new CustomEvent('leaflet:center', { detail: { id: domId, country: 'FI' }}))">FI</button>
                    <button type="button" class="btn btn-sm btn-outline"
                            @click="window.dispatchEvent(new CustomEvent('leaflet:center', { detail: { id: domId, country: 'US' }}))">US</button>
                    <button type="button" class="btn btn-sm btn-outline"
                            @click="window.dispatchEvent(new CustomEvent('leaflet:center', { detail: { id: domId, country: 'RU' }}))">RU</button>
                </div>

                <div id="{{ $mapDomId }}" wire:ignore class="w-full h-80 rounded-xl border border-base-300"></div>

                <div class="mt-3 flex justify-end gap-2">
                    <x-button class="btn-ghost"  label="{{ __('Cancel') }}" wire:click="$set('showMap', false)" />
                    <x-button class="btn-primary" label="{{ __('Use this') }}"  wire:click="$set('showMap', false)" />
                </div>
            </div>
        </x-modal>




        @assets
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
        @endassets


        @push('scripts')
            <script type="module">
                (() => {
                    if (window.__leafletBooted) return;
                    window.__leafletBooted = true;

                    const registry = window.__leafletRegistry ?? (window.__leafletRegistry = {});
                    const logPrefix = '[Leaflet-LW]';

                    const safeInvalidate = (map, note = '') => {
                        try {
                            map.invalidateSize();
                            console.debug(logPrefix, 'invalidateSize()', note);
                        } catch (err) {
                            console.warn(logPrefix, 'invalidateSize() failed', note, err);
                        }
                    };

                    const refreshById = (id, note = '') => {
                        const rec = registry[id];
                        if (!rec?.map) {
                            console.debug(logPrefix, 'refresh skipped (no map)', { id, note });
                            return;
                        }
                        try {
                            rec.map.invalidateSize();
                            const c = rec.map.getCenter();
                            rec.map.setView(c, rec.map.getZoom() || 6, { animate: false });
                            console.debug(logPrefix, 'refreshed map (center nudged)', { id, note });
                        } catch (err) {
                            console.warn(logPrefix, 'refresh failed', { id, note, err });
                        }
                    };

                    const attachIntersectionObserver = (id, el) => {
                        try {
                            const io = new IntersectionObserver((entries) => {
                                entries.forEach(entry => {
                                    if (entry.isIntersecting) {
                                        console.debug(logPrefix, 'IO visible → refresh', { id });
                                        window.dispatchEvent(new CustomEvent('leaflet:refresh', { detail: { id } }));
                                    }
                                });
                            }, { root: null, threshold: 0.01 });
                            io.observe(el);
                            console.debug(logPrefix, 'IntersectionObserver attached', { id });
                        } catch (err) {
                            console.warn(logPrefix, 'IntersectionObserver failed', { id, err });
                        }
                    };

                    const parseCoords = (coords) => {
                        if (typeof coords !== 'string' || !coords.includes('|')) return null;
                        const [latS, lngS] = coords.split('|').map(s => parseFloat(String(s).trim()));
                        if (Number.isNaN(latS) || Number.isNaN(lngS)) return null;
                        return L.latLng(latS, lngS);
                    };

                    const onLeafletInit = (e) => {
                        const { id, coords, componentId } = e.detail || {};
                        const el = document.getElementById(id);
                        console.info(logPrefix, 'init event', { id, coords, componentId, elExists: !!el });
                        if (!el) return;

                        // اگر قبلاً نقشه‌ای با همین id ساخته شده، ولی container عوض شده → ری‌اینیت فوری
                        if (registry[id]?.map && registry[id].map._container !== el) {
                            console.warn(logPrefix, 'container changed → reinit', { id });
                            try { registry[id].map.remove(); } catch (err) { console.warn(logPrefix, 'remove old map failed', { id, err }); }
                            delete registry[id];
                            // بلافاصله init مجدد با همان payload
                            queueMicrotask(() => onLeafletInit(e));
                            return;
                        }

                        // اگر اینستانس هنوز نیست → بساز
                        if (!registry[id]) {
                            console.debug(logPrefix, 'creating map instance', { id });
                            const map = L.map(el);
                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);

                            let center = [35.6892, 51.3890], zoom = 6;
                            let marker = null;

                            const latlngInit = parseCoords(coords);
                            if (latlngInit) {
                                center = [latlngInit.lat, latlngInit.lng];
                                zoom = 12;
                                marker = L.marker(latlngInit, { draggable: true }).addTo(map);
                                console.debug(logPrefix, 'initial coords applied', { id, latlng: latlngInit });
                            }

                            registry[id] = { map, marker };

                            const setPicked = (latlng) => {
                                const lat = latlng.lat.toFixed(6), lng = latlng.lng.toFixed(6);
                                const comp = window.Livewire?.find(componentId);
                                if (comp) {
                                    comp.set('mapValue', `${lat} | ${lng}`, true);
                                    console.debug(logPrefix, 'mapValue set on LW component', { id, lat, lng });
                                } else {
                                    console.warn(logPrefix, 'Livewire component not found', { componentId });
                                }
                            };

                            map.on('click', (ev) => {
                                if (registry[id].marker) registry[id].marker.setLatLng(ev.latlng);
                                else registry[id].marker = L.marker(ev.latlng, { draggable: true }).addTo(map);
                                setPicked(ev.latlng);

                                registry[id].marker.off('dragend');
                                registry[id].marker.on('dragend', (e) => setPicked(e.target.getLatLng()));
                            });

                            if (marker) marker.on('dragend', (e) => setPicked(e.target.getLatLng()));

                            // بعد از نمایش مودال (wake-ups)
                            setTimeout(() => { safeInvalidate(map, 'post-create/150ms'); map.setView(center, zoom); }, 150);
                            requestAnimationFrame(() => safeInvalidate(map, 'post-create/raf'));

                            // وقتی container visible شد، refresh
                            attachIntersectionObserver(id, el);

                            console.info(logPrefix, 'map created', { id, center, zoom });
                            return;
                        }

                        // اگر map قبلاً ساخته شده → فقط refresh/center
                        const { map, marker } = registry[id];
                        console.debug(logPrefix, 'reusing existing map', { id });

                        const latlng = parseCoords(coords);
                        if (latlng) {
                            map.setView(latlng, 18);
                            if (marker) marker.setLatLng(latlng);
                            else registry[id].marker = L.marker(latlng, { draggable: true }).addTo(map);
                            console.debug(logPrefix, 'existing map centered to coords', { id, latlng });
                        }

                        setTimeout(() => safeInvalidate(map, 'reuse/150ms'), 150);
                        requestAnimationFrame(() => safeInvalidate(map, 'reuse/raf'));
                    };

                    // رویداد اصلی
                    window.addEventListener('leaflet:init', onLeafletInit);

                    // Fallback 1: Refresh → فقط invalidateSize (و کمی سنتر)
                    window.addEventListener('leaflet:refresh', (e) => {
                        const { id } = e.detail || {};
                        console.debug(logPrefix, 'refresh event', { id });
                        refreshById(id, 'manual-event');
                    });

                    // Fallback 2: Re-Init → حذف کامل و ساخت مجدد
                    window.addEventListener('leaflet:reinit', (e) => {
                        const { id } = e.detail || {};
                        console.warn(logPrefix, 'reinit event', { id });
                        const rec = registry[id];
                        if (rec?.map) {
                            try { rec.map.remove(); } catch (err) { console.warn(logPrefix, 'remove on reinit failed', { id, err }); }
                            delete registry[id];
                        }
                        // بلافاصله init مجدد با همان payload
                        onLeafletInit(e);
                    });

                    // اختیاری: center (الان صرفاً wake-up)
                    window.addEventListener('leaflet:center', (e) => {
                        const { id, country } = e.detail || {};
                        console.debug(logPrefix, 'center event', { id, country });
                        const rec = registry[id];
                        if (!rec?.map) return;
                        safeInvalidate(rec.map, 'center-event');
                        // اگر BBOX/center کشور داری، اینجا setView کن
                        // rec.map.setView([lat, lng], zoom);
                    });

                    // Locate
                    window.addEventListener('leaflet:locate', async (e) => {
                        const { id } = e.detail || {};
                        console.debug(logPrefix, 'locate event', { id });
                        const rec = registry[id];
                        if (!rec?.map || !navigator.geolocation) return;
                        navigator.geolocation.getCurrentPosition(
                            (pos) => {
                                const latlng = L.latLng(pos.coords.latitude, pos.coords.longitude);
                                rec.map.setView(latlng, 13);
                                if (rec.marker) rec.marker.setLatLng(latlng);
                                else rec.marker = L.marker(latlng, { draggable: true }).addTo(rec.map);
                                console.debug(logPrefix, 'located + centered', { id, latlng });
                                safeInvalidate(rec.map, 'locate/post-center');
                            },
                            (err) => { console.warn(logPrefix, 'locate failed', { id, err }); safeInvalidate(rec?.map, 'locate/fallback'); },
                            { enableHighAccuracy: true, timeout: 5000, maximumAge: 0 }
                        );
                    });

                    // Livewire hooks
                    const attachHooks = () => {
                        window.Livewire.hook('element.removed', (el) => {
                            const id = el?.id;
                            if (!id) return;
                            if (registry[id]?.map) {
                                console.debug(logPrefix, 'element.removed → remove map', { id });
                                try { registry[id].map.remove(); } catch (err) { console.warn(logPrefix, 'remove on element.removed failed', { id, err }); }
                                delete registry[id];
                            }
                        });

                        window.Livewire.hook('message.processed', () => {
                            console.debug(logPrefix, 'LW message.processed → refresh all maps');
                            Object.entries(registry).forEach(([id, rec]) => {
                                if (!rec?.map) return;
                                try { rec.map.invalidateSize(); } catch (err) { console.warn(logPrefix, 'invalidate in processed failed', { id, err }); }
                            });
                        });
                        console.info(logPrefix, 'Livewire hooks attached');
                    };

                    if (window.Livewire) attachHooks();
                    else document.addEventListener('livewire:initialized', attachHooks, { once: false });

                })();
            </script>
        @endpush






</div>


