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
        var color     = cfg.markerColor  || '#C8A96E';
        var size      = cfg.markerSize   || 40;
        var shape     = cfg.markerShape  || 'circle';
        var showCount = cfg.markerShowCount !== undefined ? cfg.markerShowCount : true;
        var showLabel  = cfg.markerShowLabel  || false;
        var labelSize  = cfg.markerLabelSize  || 11;
        var labelColor = cfg.markerLabelColor || '#1a1a1a';
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

        /* Close popup on map click */
        map.addChild(new ymaps3.YMapListener({
            onClick: function () {
                if (currentPopup) { map.removeChild(currentPopup); currentPopup = null; }
            },
        }));

        if (!markers.length) return;

        /* Markers */
        var allMarkerObjects = [];
        var markerEls = []; /* {dot, label} refs for zoom scaling */
        var baseZoom = zoom;

        function calcScale(currentZoom) {
            var delta = currentZoom - baseZoom;
            var s = Math.pow(1.18, delta);
            return Math.max(1, s); /* never shrink below base size */
        }

        function applyScale(s) {
            markerEls.forEach(function (refs) {
                var sz = Math.round(size * s);
                refs.dot.style.width  = sz + 'px';
                refs.dot.style.height = sz + 'px';
                refs.dot.style.fontSize = Math.round(sz * 0.3) + 'px';
                refs.dot.style.borderRadius = shape === 'square' ? '0' : '50%';
                if (refs.label) {
                    refs.label.style.fontSize = Math.round(labelSize * s) + 'px';
                }
            });
        }

        function makeMarkerEl(m) {
            var wrap = document.createElement('div');
            wrap.className = 'hpm-marker-wrap';
            wrap.style.cssText = 'display:flex;align-items:center;gap:7px;cursor:pointer;';
            wrap.title = m.title;

            var dot = document.createElement('div');
            dot.className = 'hpm-dot';
            dot.style.cssText = [
                'width:'  + size + 'px',
                'height:' + size + 'px',
                'border-radius:' + (shape === 'square' ? '0' : '50%'),
                'background:' + color,
                'flex-shrink:0',
                'display:flex',
                'align-items:center',
                'justify-content:center',
                'color:#fff',
                'font-size:' + Math.round(size * 0.3) + 'px',
                'font-weight:700',
                'user-select:none',
                'transition:width .15s,height .15s,font-size .15s',
            ].join(';');

            if (showCount) {
                dot.textContent = m.count;
            }

            wrap.appendChild(dot);

            var labelEl = null;
            if (showLabel) {
                labelEl = document.createElement('span');
                labelEl.className = 'hpm-marker-label';
                labelEl.textContent = m.title;
                labelEl.style.cssText = [
                    'font-size:' + labelSize + 'px',
                    'font-weight:800',
                    'text-transform:uppercase',
                    'letter-spacing:.04em',
                    'color:' + labelColor,
                    'white-space:nowrap',
                    'pointer-events:none',
                    'transition:font-size .15s',
                ].join(';');
                wrap.appendChild(labelEl);
            }

            markerEls.push({ dot: dot, label: labelEl });
            return wrap;
        }

        markers.forEach(function (m) {
            var el = makeMarkerEl(m);
            var marker = new ymaps3.YMapMarker({ coordinates: [m.lng, m.lat] }, el);
            el.addEventListener('click', function (e) {
                e.stopPropagation();
                openPopup(map, m, [m.lng, m.lat], color, size);
            });
            allMarkerObjects.push({ marker: marker, data: m, inMap: true });
            map.addChild(marker);
        });

        /* Scale markers on zoom */
        map.addChild(new ymaps3.YMapListener({
            onUpdate: function (update) {
                if (update.location && update.location.zoom !== undefined) {
                    applyScale(calcScale(update.location.zoom));
                }
            },
        }));

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

        /* Sidebar search */
        var searchInput = wrapper.querySelector('.horizons-partners-map__search-input');
        if (searchInput) {
            searchInput.addEventListener('input', function () {
                var q = searchInput.value.trim().toLowerCase();
                var groups = wrapper.querySelectorAll('.horizons-partners-map__country-group');
                groups.forEach(function (group) {
                    var btn = group.querySelector('.horizons-partners-map__filter-btn');
                    var name = btn ? btn.textContent.trim().toLowerCase() : '';
                    group.style.display = (!q || name.indexOf(q) !== -1) ? '' : 'none';
                });
                /* standalone region buttons */
                var regionBtns = wrapper.querySelectorAll('.horizons-partners-map__sidebar-inner > .horizons-partners-map__filter-region');
                regionBtns.forEach(function (btn) {
                    var name = btn.textContent.trim().toLowerCase();
                    btn.style.display = (!q || name.indexOf(q) !== -1) ? '' : 'none';
                });
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

                if (autoFitBounds) {
                    if (filter === 'all' && markers.length > 1) {
                        var alllngs = markers.map(function (m) { return m.lng; });
                        var alllats = markers.map(function (m) { return m.lat; });
                        map.setLocation({
                            bounds: [
                                [Math.min.apply(null, alllngs), Math.min.apply(null, alllats)],
                                [Math.max.apply(null, alllngs), Math.max.apply(null, alllats)],
                            ],
                            duration: 400,
                        });
                    } else if (filter !== 'all') {
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
                }
            });
        });
    }

    /* Popup */
    var currentPopup = null;

    function openPopup(map, markerData, coords, accentColor, markerSize) {
        if (currentPopup) {
            map.removeChild(currentPopup);
            currentPopup = null;
        }

        var offset = (markerSize || 40) + 8;

        var container = document.createElement('div');
        container.style.cssText = [
            'background:#fff',
            'border-radius:8px',
            'box-shadow:0 4px 24px rgba(0,0,0,.18)',
            'min-width:260px',
            'max-width:320px',
            'overflow:hidden',
            'font-family:inherit',
            'margin-top:' + offset + 'px',
        ].join(';');

        var closeBtn = document.createElement('button');
        closeBtn.style.cssText = 'position:absolute;top:6px;right:8px;background:none;border:none;font-size:18px;cursor:pointer;color:#aaa;line-height:1;padding:0;z-index:1;';
        closeBtn.textContent = '×';
        closeBtn.addEventListener('click', function () {
            if (currentPopup) { map.removeChild(currentPopup); currentPopup = null; }
        });

        var body = document.createElement('div');
        body.className = 'hpm-popup__body';
        body.style.position = 'relative';
        body.innerHTML = '<div style="padding:16px;text-align:center;color:#aaa;font-size:13px;">Loading…</div>';
        body.appendChild(closeBtn);

        container.appendChild(body);

        var popup = new ymaps3.YMapMarker({ coordinates: coords }, container);
        map.addChild(popup);
        currentPopup = popup;

        var url = '/wp-json/wp/v2/partners?per_page=100&_embed=wp:featuredmedia&_fields=id,title,link,meta,_embedded,featured_media&partner_' + markerData.termType + '=' + markerData.termId;

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
            var href = p.link || '#';

            var imgTag = '';
            var media  = p._embedded && p._embedded['wp:featuredmedia'] && p._embedded['wp:featuredmedia'][0];
            if (media && !media.code) {
                var sizes = media.media_details && media.media_details.sizes;
                var src   = (sizes && (sizes.thumbnail || sizes.medium))
                    ? (sizes.thumbnail || sizes.medium).source_url
                    : media.source_url;
                if (src) {
                    imgTag = '<img decoding="async" loading="lazy" class="w-48 h-48 me-3 rounded-circle" src="' + src + '" alt="' + name + '">';
                }
            }
            if (!imgTag) {
                imgTag = '<div class="w-48 h-48 me-3 rounded-circle bg-light d-flex align-items-center justify-content-center flex-shrink-0" style="font-size:20px;">👤</div>';
            }

            html += '<div class="author-info d-flex align-items-center px-3 py-2 border-bottom hpm-card-row">'
                +   imgTag
                +   '<div class="avatar-info mt-0 overflow-hidden">'
                +     '<a href="' + href + '" class="hover-7 link-body label-u text-charcoal-blue d-block lh-0 text-truncate" target="_blank" rel="noopener">' + name + '</a>'
                +     (pos ? '<span class="body-s lh-0 text-neutral-500 d-block mt-1 text-truncate">' + pos + '</span>' : '')
                +   '</div>'
                + '</div>';
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
