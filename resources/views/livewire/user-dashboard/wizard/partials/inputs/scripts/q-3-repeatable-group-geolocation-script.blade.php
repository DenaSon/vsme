@script
<script>



    // رجیستری: picked را هم نگه می‌داریم
    const _leafletRegistry = {}; // { [id]: { map, marker, accuracyCircle, picked, lastInit } }

    window.addEventListener('open-map', async (e) => {
        const { id, coords, componentId } = e.detail || {};
        const el = document.getElementById(id);
        if (!el) return;

        if (!_ensureSingleInit(id)) return;
        _hardDestroyMap(el, id);

        const map = L.map(id);
        const layer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);
        layer.on('tileerror', ev => { try { ev.tile.src = ev.tile.src + '&retry=' + Date.now(); } catch(e) {} });

        let marker = null;
        _leafletRegistry[id] = { map, marker: null, accuracyCircle: null, picked: null, lastInit: Date.now(), componentId };

        map.on('click', function (ev) {
            const lat = ev.latlng.lat.toFixed(6);
            const lng = ev.latlng.lng.toFixed(6);

            if (_leafletRegistry[id].marker) _leafletRegistry[id].marker.setLatLng(ev.latlng);
            else _leafletRegistry[id].marker = L.marker(ev.latlng).addTo(map);

            // فقط در حافظهٔ کلاینت نگه دار؛ هیچ set/call به لایووایر نکن
            _leafletRegistry[id].picked = `${lat} | ${lng}`;

            // پیش‌نمایش را آپدیت کن (اختیاری)
            const preview = document.getElementById(id + '-preview');
            if (preview) preview.textContent = _leafletRegistry[id].picked;
        });

        // مرکز اولیه
        let center = [30.862374, 51.456771];
        let hasCoords = false;
        if (typeof coords === 'string' && coords.includes('|')) {
            const parts = coords.split('|').map(s => parseFloat(s.trim()));
            if (parts.length === 2 && parts.every(n => !isNaN(n))) {
                center = [parts[0], parts[1]];
                _leafletRegistry[id].marker = L.marker(center).addTo(map);
                hasCoords = true;
                _leafletRegistry[id].picked = coords;
                const preview = document.getElementById(id + '-preview');
                if (preview) preview.textContent = coords;
            }
        }

        setTimeout(() => { map.invalidateSize(); map.setView(center, hasCoords ? 12 : 6); }, 200);
        setTimeout(() => map.invalidateSize(), 500);
    });

    // دکمهٔ تأیید: از رجیستری بخوان و تازه اینجا Livewire را صدا بزن
    window.commitPicked = function(id) {
        const reg = _leafletRegistry[id];
        if (!reg || !reg.map) return;
        const picked = reg.picked;
        if (!picked) { alert('Pick a location by clicking on the map.'); return; }

        const comp = window.Livewire.find(reg.componentId);
        if (comp) comp.call('confirmPickFromJs', picked); // متد جدید در php
    };





</script>
@endscript



