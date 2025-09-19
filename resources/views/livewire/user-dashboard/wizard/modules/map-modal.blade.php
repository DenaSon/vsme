<x-modal wire:model="showMap" wire:key="map-modal" title="{{ __('Select on map') }}">
    <div
        x-data="{
            open: @entangle('showMap').live,
            domId: @entangle('mapDomId').live,
            compId: '{{$this->id()}}',
            coords: @js($mapValue)
        }"
        x-init="
            const fire = () => window.dispatchEvent(new CustomEvent('leaflet:init', {
                detail: { id: domId, coords: coords, componentId: compId }
            }));
            $watch('open', v => { if (v && domId) queueMicrotask(() => requestAnimationFrame(fire)); });
            $watch('domId', id => { if (open && id) queueMicrotask(() => requestAnimationFrame(fire)); });
        "
    >
        <div id="{{ $mapDomId }}" wire:ignore class="w-full h-80 rounded-xl border border-base-300"></div>

        <div class="mt-3 flex justify-end gap-2">
            <x-button class="btn-ghost" label="{{ __('Cancel') }}" wire:click="$set('showMap', false)" />
            <x-button class="btn-primary" label="{{ __('Use this') }}"
                      @click="window.dispatchEvent(new CustomEvent('leaflet:commit', { detail: { id: domId, componentId: compId } })); $wire.set('showMap', false)" />
        </div>
    </div>



</x-modal>



@script
<script>
    (() => {
        if (window.__MAP1__) return; window.__MAP1__ = true;


        const R = {};
        const log = (...a) => console.log('[MAP1]', ...a);

        const parse = v => {
            if (!v || typeof v !== 'string' || !v.includes('|')) return null;
            const [a,b] = v.split('|').map(s => parseFloat(String(s).trim()));
            if (Number.isNaN(a) || Number.isNaN(b)) return null;
            return L.latLng(a,b);
        };

        const toStr = ll => `${ll.lat.toFixed(6)} | ${ll.lng.toFixed(6)}`;

        function init(e){
            const { id, coords, componentId } = e.detail || {};
            log('init', id, 'coords=', coords);

            if (!window.L) { log('L not ready → retry'); return setTimeout(() => init(e), 2); }
            const el = document.getElementById(id);
            if (!el)      { log('container missing → retry'); return setTimeout(() => init(e), 2); }


            if (R[id]?.map && R[id].map._container !== el) {
                try { R[id].map.remove(); } catch {}
                delete R[id];
                log('container changed → recreate', id);
            }

            if (!R[id]) {
                const map = L.map(el);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);

                let marker = null;
                const p0 = parse(coords);
                if (p0) { marker = L.marker(p0, { draggable: true }).addTo(map); map.setView(p0, 12); }
                else    { map.setView([60.1699, 24.9384], 15); }

                const rec = R[id] = { map, marker, el, compId: componentId, pending: coords || null };

                map.on('click', ev => {
                    if (rec.marker) rec.marker.setLatLng(ev.latlng);
                    else rec.marker = L.marker(ev.latlng, { draggable: true }).addTo(map);
                    rec.pending = toStr(ev.latlng);
                    log('changed (pending)', rec.pending);
                    rec.marker.off('dragend'); rec.marker.on('dragend', () => { rec.pending = toStr(rec.marker.getLatLng()); log('changed (pending)', rec.pending); });
                });

                setTimeout(() => { try { map.invalidateSize(); } catch {} }, 120);
                requestAnimationFrame(() => { try { map.invalidateSize(); } catch {} });

                log('created', id);
                return;
            }

            try { R[id].map.invalidateSize(); } catch {}
            log('reused + invalidate', id);
        }


        function commit(e){
            const { id, componentId } = e.detail || {};
            const rec = R[id];
            if (!rec) return log('commit skipped (no rec)', id);
            const val = rec.pending || null;
            const comp = window.Livewire?.find(componentId);
            if (comp && val) { comp.set('mapValue', val, true); log('set mapValue →', val); }
            else log('commit skipped (no comp or no val)', { componentId, val });
        }

        window.addEventListener('leaflet:init', init);
        window.addEventListener('leaflet:commit', commit);


        const hook = () => {
            window.Livewire.hook('element.removed', el => {
                const id = el?.id; if (!id || !R[id]?.map) return;
                try { R[id].map.remove(); } catch {}
                delete R[id];
                log('cleanup removed', id);
            });
        };
        if (window.Livewire) hook(); else document.addEventListener('livewire:initialized', hook, { once:true });
    })();
</script>
@endscript
