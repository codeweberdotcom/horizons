/* Partners Map — frontend script (Yandex Maps API v3) */
(function () {
    'use strict';

    document.querySelectorAll('.horizons-partners-map').forEach(function (wrapper) {
        var raw = wrapper.getAttribute('data-map-config');
        if (!raw) return;

        var cfg;
        try { cfg = JSON.parse(raw); } catch (e) { return; }

        var canvas = wrapper.querySelector('.horizons-partners-map__canvas');
        if (!canvas) return;

        ymaps3.ready.then(function () {
            initMap(wrapper, canvas, cfg);
        });
    });

    function initMap(wrapper, canvas, cfg) {
        var center    = cfg.center    || [37.64, 55.76];
        var zoom      = cfg.zoom      || 4;
        var mapType   = cfg.mapType   || 'normal';
        var markers   = cfg.markers   || [];
        var color     = cfg.markerColor || '#C8A96E';
        var size      = cfg.markerSize  || 40;
        var styleJson = cfg.styleJson   || '';
        var scrollZoom    = cfg.scrollZoom    || false;
        var autoFitBounds = cfg.autoFitBounds !== undefined ? cfg.autoFitBounds : true;

        /* Map type */
        var typeId = 'normal';
        if (mapType === 'satellite') typeId = 'satellite';
        if (mapType === 'hybrid')    typeId = 'hybrid';

        /* Base layer */
        var schemeOptions = { theme: typeId };
        if (styleJson) {
            try {
                var parsedStyle = JSON.parse(styleJson);
                schemeOptions.customization = parsedStyle;
            } catch (e) {}
        }

        /* Create map */
        var map = new ymaps3.YMap(canvas, {
            location: { center: center, zoom: zoom },
            behaviors: scrollZoom
                ? ['drag', 'scrollZoom', 'pinchZoom', 'dblClick']
                : ['drag', 'pinchZoom', 'dblClick'],
        });

        map.addChild(new ymaps3.YMapDefaultSchemeLayer(schemeOptions));
        map.addChild(new ymaps3.YMapDefaultFeaturesLayer());

        if (!markers.length) return;

        /* Markers */
        var allMarkerObjects = [];

        function makeMarkerEl(m) {
            var el = document.createElement('div');
            el.className = 'hpm-dot';
            el.style.cssText = [
                'width:'  + size + 'px',
                'height:' + size + 'px',
                'border-radius:50%',
                'background:' + color,
                'cursor:pointer',
                'display:flex',
                'align-items:center',
                'justify-content:center',
                'color:#fff',
                'font-size:' + Math.round(size * 0.3) + 'px',
                'font-weight:700',
                'user-select:none',
            ].join(';');
            el.textContent = m.count;
            el.title = m.title;
            return el;
        }

        markers.forEach(function (m) {
            var el = makeMarkerEl(m);
            var marker = new ymaps3.YMapMarker({ coordinates: [m.lng, m.lat] }, el);
            el.addEventListener('click', function (e) {
                e.stopPropagation();
                openPopup(map, m, [m.lng, m.lat], color);
            });
            allMarkerObjects.push({ marker: marker, data: m, inMap: true });
            map.addChild(marker);
        });

        /* Auto fit bounds */
        if (autoFitBounds && markers.length > 1) {
            var lngs = markers.map(function (m) { return m.lng; });
            var lats = markers.map(function (m) { return m.lat; });
            map.setLocation({
                bounds: [
                    [Math.min.apply(null, lngs), Math.min.apply(null, lats)],
                    [Math.max.apply(null, lngs), Math.max.apply(null, lats)],
                ],
                duration: 0,
            });
        }

        /* Sidebar filter */
        var buttons = wrapper.querySelectorAll('.horizons-partners-map__filter-btn');

        buttons.forEach(function (btn) {
            btn.addEventListener('click', function () {
                buttons.forEach(function (b) { b.classList.remove('is-active'); });
                btn.classList.add('is-active');

                var filter = btn.getAttribute('data-filter');
                var termId = parseInt(btn.getAttribute('data-term-id') || '0', 10);

                allMarkerObjects.forEach(function (obj) {
                    var show = filter === 'all' || obj.data.termId === termId;
                    if (show && !obj.inMap) {
                        map.addChild(obj.marker);
                        obj.inMap = true;
                    } else if (!show && obj.inMap) {
                        map.removeChild(obj.marker);
                        obj.inMap = false;
                    }
                });

                if (filter !== 'all' && autoFitBounds) {
                    var visible = allMarkerObjects.filter(function (o) { return o.data.termId === termId; });
                    if (visible.length === 1) {
                        map.setLocation({ center: [visible[0].data.lng, visible[0].data.lat], zoom: 6, duration: 400 });
                    } else if (visible.length > 1) {
                        var vlngs = visible.map(function (o) { return o.data.lng; });
                        var vlats = visible.map(function (o) { return o.data.lat; });
                        map.setLocation({
                            bounds: [
                                [Math.min.apply(null, vlngs), Math.min.apply(null, vlats)],
                                [Math.max.apply(null, vlngs), Math.max.apply(null, vlats)],
                            ],
                            duration: 400,
                        });
                    }
                }
            });
        });
    }

    /* Popup */
    var currentPopup = null;

    function openPopup(map, markerData, coords, accentColor) {
        if (currentPopup) {
            map.removeChild(currentPopup);
            currentPopup = null;
        }

        var container = document.createElement('div');
        container.style.cssText = 'background:#fff;border-radius:8px;box-shadow:0 4px 24px rgba(0,0,0,.18);min-width:260px;max-width:320px;overflow:hidden;font-family:inherit;';

        var header = document.createElement('div');
        header.className = 'hpm-popup__header';
        header.style.position = 'relative';
        header.textContent = markerData.title;

        var closeBtn = document.createElement('button');
        closeBtn.style.cssText = 'position:absolute;top:50%;right:10px;transform:translateY(-50%);background:none;border:none;font-size:20px;cursor:pointer;color:#aaa;line-height:1;padding:0;';
        closeBtn.textContent = '×';
        closeBtn.addEventListener('click', function () {
            if (currentPopup) { map.removeChild(currentPopup); currentPopup = null; }
        });
        header.appendChild(closeBtn);

        var body = document.createElement('div');
        body.className = 'hpm-popup__body';
        body.innerHTML = '<div style="padding:16px;text-align:center;color:#aaa;font-size:13px;">Loading…</div>';

        container.appendChild(header);
        container.appendChild(body);

        var popup = new ymaps3.YMapMarker({ coordinates: coords }, container);
        map.addChild(popup);
        currentPopup = popup;

        var url = '/wp-json/wp/v2/partners?per_page=100&_fields=id,title,link,meta&partner_' + markerData.termType + '=' + markerData.termId;

        fetch(url)
            .then(function (r) { return r.json(); })
            .then(function (partners) { renderPartners(body, partners); })
            .catch(function () {
                body.innerHTML = '<div style="padding:16px;color:#c00;font-size:13px;">Failed to load partners.</div>';
            });
    }

    function renderPartners(body, partners) {
        if (!partners || !partners.length) {
            body.innerHTML = '<div style="padding:16px;text-align:center;color:#aaa;font-size:13px;">No partners found.</div>';
            return;
        }

        var shown = partners.slice(0, 8);
        var html = '';

        shown.forEach(function (p) {
            var name = p.title && p.title.rendered ? p.title.rendered : '—';
            var pos  = p.meta && p.meta._partner_position ? p.meta._partner_position : '';
            html += '<a href="' + (p.link || '#') + '" class="hpm-partner-card" target="_blank" rel="noopener">'
                + '<div class="hpm-partner-card__avatar">👤</div>'
                + '<div class="hpm-partner-card__info">'
                + '<span class="hpm-partner-card__name">' + name + '</span>'
                + (pos ? '<span class="hpm-partner-card__pos">' + pos + '</span>' : '')
                + '</div>'
                + '</a>';
        });

        body.innerHTML = html;

        if (partners.length > 8) {
            var more = document.createElement('a');
            more.className = 'hpm-popup__more';
            more.href = '/partners/';
            more.textContent = 'All partners (' + partners.length + ')';
            body.parentNode.appendChild(more);
        }
    }
})();
