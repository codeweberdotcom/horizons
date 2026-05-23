/* Partners Map — frontend script (Yandex Maps API v3) */
(function () {
    'use strict';

    /* ── Partner data cache (15-min TTL) ──────────────────────────────────── */
    var partnersCache = {};
    var CACHE_TTL = 15 * 60 * 1000;

    function cacheKey(termType, termId) {
        return termType + '_' + termId;
    }

    function getCached(key) {
        var entry = partnersCache[key];
        if (!entry) return null;
        if (Date.now() - entry.ts > CACHE_TTL) { delete partnersCache[key]; return null; }
        return entry.data;
    }

    function setCache(key, data) {
        partnersCache[key] = { data: data, ts: Date.now() };
    }

    function fetchPartners(termType, termId) {
        var key = cacheKey(termType, termId);
        var hit = getCached(key);
        if (hit) return Promise.resolve(hit);
        var url = '/wp-json/wp/v2/partners?per_page=100&_embed=wp:featuredmedia&_fields=id,title,link,meta,_embedded,featured_media,_links&partner_' + termType + '=' + termId;
        return fetch(url)
            .then(function (r) { return r.json(); })
            .then(function (data) { setCache(key, data); return data; });
    }

    /* Preload all markers' partners in the background after map init */
    function preloadAllPartners(markers) {
        markers.forEach(function (m) {
            var key = cacheKey(m.termType, m.termId);
            if (getCached(key)) return;
            fetchPartners(m.termType, m.termId).catch(function () {});
        });
    }

    /* ── Map init ─────────────────────────────────────────────────────────── */
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
            zoomRange: { min: cfg.zoomMin || 2, max: cfg.zoomMax || 19 },
            behaviors: ['drag', 'scrollZoom', 'pinchZoom', 'dblClick'],
        });

        map.addChild(new ymaps3.YMapDefaultSchemeLayer(schemeOptions));
        map.addChild(new ymaps3.YMapDefaultFeaturesLayer());

        /* Zoom control buttons */
        if (cfg.zoomControl !== false) {
            var zoomWrap = document.createElement('div');
            zoomWrap.style.cssText = 'position:absolute;right:12px;bottom:80px;z-index:20;display:flex;flex-direction:column;gap:4px;pointer-events:auto;';
            var zoomBtnCss = [
                'width:36px','height:36px',
                'background:rgba(30,38,42,0.82)',
                'backdrop-filter:blur(8px) saturate(160%)',
                '-webkit-backdrop-filter:blur(8px) saturate(160%)',
                'border:1px solid rgba(255,255,255,.12)',
                'color:rgba(255,255,255,.9)',
                'font-size:22px','font-weight:300','line-height:1',
                'cursor:pointer','display:flex','align-items:center','justify-content:center',
            ].join(';');
            ['+', '−'].forEach(function (label, i) {
                var btn = document.createElement('button');
                btn.textContent = label;
                btn.style.cssText = zoomBtnCss;
                btn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    if (!map.location) return;
                    map.setLocation({ zoom: map.location.zoom + (i === 0 ? 1 : -1), duration: 200 });
                });
                zoomWrap.appendChild(btn);
            });
            canvas.appendChild(zoomWrap);
        }

        /* Close popup on map click */
        map.addChild(new ymaps3.YMapListener({
            onClick: function () {
                if (currentPopup) { map.removeChild(currentPopup); currentPopup = null; currentPopupContainer = null; }
            },
        }));

        if (!markers.length) return;

        /* Markers */
        var allMarkerObjects = [];
        var markerEls = [];
        var baseZoom = zoom;

        function calcScale(currentZoom) {
            var delta = currentZoom - baseZoom;
            var s = Math.pow(1.18, delta);
            return Math.max(1, s);
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
            if (currentPopupContainer) {
                currentPopupContainer.style.marginTop = (Math.round(size * s / 2) + 16) + 'px';
            }
        }

        function makeDotCss() {
            return [
                'width:'  + size + 'px',
                'height:' + size + 'px',
                'border-radius:' + (shape === 'square' ? '0' : '50%'),
                'background:' + color,
                'display:flex',
                'align-items:center',
                'justify-content:center',
                'color:#fff',
                'font-size:' + Math.round(size * 0.3) + 'px',
                'font-weight:700',
                'user-select:none',
                'transition:width .15s,height .15s,font-size .15s',
            ].join(';');
        }

        function makeLabelCss(offsetX, offsetY, alignRight) {
            return [
                'position:absolute',
                'left:' + offsetX + 'px',
                'top:' + offsetY + 'px',
                'transform:' + (alignRight ? 'translate(-100%,-50%)' : 'translateY(-50%)'),
                'font-size:' + labelSize + 'px',
                'font-weight:800',
                'text-transform:uppercase',
                'letter-spacing:.04em',
                'color:' + labelColor,
                'white-space:nowrap',
                'cursor:pointer',
            ].join(';');
        }

        function makeMarkerEl(m) {
            var wrap = document.createElement('div');
            wrap.className = 'hpm-marker-wrap';
            wrap.title = m.title;

            var dot = document.createElement('div');
            dot.className = 'hpm-dot';

            if (showCount) {
                dot.textContent = m.count;
            }

            var labelEl = null;

            if (showLabel) {
                /* Anchor layout: wrap is a zero-size anchor at the coordinate point */
                wrap.style.cssText = 'position:relative;width:0;height:0;cursor:pointer;display:block;';
                dot.style.cssText = makeDotCss() + ';position:absolute;transform:translate(-50%,-50%);';
                wrap.appendChild(dot);

                labelEl = document.createElement('span');
                labelEl.className = 'hpm-marker-label';
                labelEl.textContent = m.title;
                labelEl.style.cssText = makeLabelCss(size / 2 + 6, 0, false);
                wrap.appendChild(labelEl);

                /* Hover: combine translate + scale because CSS rule would overwrite translate */
                wrap.addEventListener('mouseover', function () { dot.style.transform = 'translate(-50%,-50%) scale(1.15)'; });
                wrap.addEventListener('mouseout',  function () { dot.style.transform = 'translate(-50%,-50%)'; });
            } else {
                /* Flex layout (dot only, no label) */
                wrap.style.cssText = 'display:flex;align-items:center;gap:7px;cursor:pointer;transform:translate(0,-50%);';
                dot.style.cssText = makeDotCss() + ';flex-shrink:0;';
                wrap.appendChild(dot);
            }

            markerEls.push({ dot: dot, label: labelEl, svg: null });
            return wrap;
        }

        function applyLeaderLines() {
            if (!showLabel) return;
            var canvasRect = canvas.getBoundingClientRect();
            if (!canvasRect.width || !canvasRect.height) return;

            /* Real pixel positions via DOM — no tile-math */
            var pos = markerEls.map(function (refs) {
                if (!refs || !refs.dot) return null;
                var r = refs.dot.getBoundingClientRect();
                if (!r.width && !r.height) return null;
                return { x: r.left + r.width / 2 - canvasRect.left, y: r.top + r.height / 2 - canvasRect.top };
            });

            var lw = markerEls.map(function (refs, idx) {
                if (!refs || !refs.dot) return 0;
                var dotW = refs.dot.getBoundingClientRect().width || size;
                return dotW / 2 + 6 + markers[idx].title.length * Math.ceil(labelSize * 0.65) + 8;
            });
            var lh = labelSize + 6;

            /* Detect label bbox overlaps */
            var side = new Array(markers.length).fill(0); /* 0=none, -1=left, +1=right */

            for (var i = 0; i < markers.length; i++) {
                for (var j = i + 1; j < markers.length; j++) {
                    /* Default label box for i: from pos[i].x to pos[i].x + lw[i], y ± lh/2 */
                    var xi1 = pos[i].x, xi2 = pos[i].x + lw[i];
                    var yi1 = pos[i].y - lh / 2, yi2 = pos[i].y + lh / 2;
                    var xj1 = pos[j].x, xj2 = pos[j].x + lw[j];
                    var yj1 = pos[j].y - lh / 2, yj2 = pos[j].y + lh / 2;

                    if (xi1 < xj2 && xi2 > xj1 && yi1 < yj2 && yi2 > yj1) {
                        /* i is left of j → i goes left-up, j stays right */
                        if (pos[i].x <= pos[j].x) {
                            if (!side[i]) side[i] = -1;
                        } else {
                            if (!side[j]) side[j] = -1;
                        }
                    }
                }
            }

            var lineLen = 50;

            markers.forEach(function (m, idx) {
                var refs = markerEls[idx];
                if (!refs || !refs.label) return;
                var wrap = refs.dot.parentNode;
                if (!wrap) return;

                /* Remove previous SVG */
                if (refs.svg) {
                    try { wrap.removeChild(refs.svg); } catch (e) {}
                    refs.svg = null;
                }

                var dir = side[idx]; /* -1=upper-left, 0=no leader, +1 unused (label stays right) */

                if (dir === -1) {
                    var ldx = -lineLen;
                    var ldy = -lineLen;

                    /* SVG leader line */
                    var svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
                    svg.style.cssText = 'position:absolute;overflow:visible;left:0;top:0;pointer-events:none;';
                    svg.setAttribute('width', '0');
                    svg.setAttribute('height', '0');
                    var line = document.createElementNS('http://www.w3.org/2000/svg', 'line');
                    line.setAttribute('x1', '0'); line.setAttribute('y1', '0');
                    line.setAttribute('x2', String(ldx)); line.setAttribute('y2', String(ldy));
                    line.setAttribute('stroke', 'rgba(255,255,255,0.75)');
                    line.setAttribute('stroke-width', '1.5');
                    svg.appendChild(line);
                    wrap.insertBefore(svg, refs.dot);
                    refs.svg = svg;

                    refs.label.style.cssText = makeLabelCss(ldx - 4, ldy, true);
                } else {
                    /* Default: label to the right of dot */
                    refs.label.style.cssText = makeLabelCss(size / 2 + 6, 0, false);
                }
            });
        }

        markers.forEach(function (m) {
            var el = makeMarkerEl(m);
            var marker = new ymaps3.YMapMarker({ coordinates: [m.lng, m.lat] }, el);
            el.addEventListener('click', function (e) {
                e.stopPropagation();
                openPopup(map, canvas, m, [m.lng, m.lat], color, size);
            });
            allMarkerObjects.push({ marker: marker, data: m, inMap: true });
            map.addChild(marker);
        });

        /* Preload all partner data in background */
        preloadAllPartners(markers);

        /* Scale markers on zoom + re-check leader lines */
        var leaderDebounce = null;
        map.addChild(new ymaps3.YMapListener({
            onUpdate: function (update) {
                if (update.location && update.location.zoom !== undefined) {
                    applyScale(calcScale(update.location.zoom));
                    if (showLabel) {
                        clearTimeout(leaderDebounce);
                        leaderDebounce = setTimeout(applyLeaderLines, 300);
                    }
                }
            },
        }));

        /* Initial leader lines after autoFitBounds animation settles */
        if (showLabel) { setTimeout(applyLeaderLines, 600); }

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

        /* Sidebar toggle */
        var toggleBtn = wrapper.querySelector('.horizons-partners-map__toggle-btn');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', function () {
                wrapper.classList.toggle('is-sidebar-collapsed');
            });
        }

        var closeBtn = wrapper.querySelector('.horizons-partners-map__sidebar-close-btn');
        if (closeBtn) {
            closeBtn.addEventListener('click', function () {
                wrapper.classList.add('is-sidebar-collapsed');
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

                if (currentPopup) { map.removeChild(currentPopup); currentPopup = null; currentPopupContainer = null; }

                if (filter === 'all') {
                    if (markers.length > 1) {
                        var alllngs = markers.map(function (m) { return m.lng; });
                        var alllats = markers.map(function (m) { return m.lat; });
                        map.setLocation({
                            bounds: [
                                [Math.min.apply(null, alllngs), Math.min.apply(null, alllats)],
                                [Math.max.apply(null, alllngs), Math.max.apply(null, alllats)],
                            ],
                            duration: 400,
                        });
                    }
                } else {
                    var target = allMarkerObjects.filter(function (o) { return o.data.termId === termId; })[0];

                    if (!target) {
                        /* Country with no direct marker — fit to its region markers */
                        var regionTargets = allMarkerObjects.filter(function (o) { return o.data.parentId === termId; });
                        if (!regionTargets.length) return;

                        if (regionTargets.length === 1) {
                            map.setLocation({ center: [regionTargets[0].data.lng, regionTargets[0].data.lat], zoom: 6, duration: 400 });
                            openPopup(map, canvas, regionTargets[0].data, [regionTargets[0].data.lng, regionTargets[0].data.lat], color, size, 450, false);
                        } else {
                            var rlngs = regionTargets.map(function (o) { return o.data.lng; });
                            var rlats = regionTargets.map(function (o) { return o.data.lat; });
                            map.setLocation({
                                bounds: [
                                    [Math.min.apply(null, rlngs), Math.min.apply(null, rlats)],
                                    [Math.max.apply(null, rlngs), Math.max.apply(null, rlats)],
                                ],
                                duration: 400,
                            });
                        }
                        return;
                    }

                    map.setLocation({ center: [target.data.lng, target.data.lat], zoom: 6, duration: 400 });
                    openPopup(map, canvas, target.data, [target.data.lng, target.data.lat], color, size, 450, false);
                }
            });
        });
    }

    /* ── Popup ────────────────────────────────────────────────────────────── */
    var currentPopup = null;
    var currentPopupContainer = null;

    function makePill() {
        var el = document.createElement('div');
        el.style.cssText = [
            'background:#fff',
            'border-radius:50px',
            'box-shadow:0 4px 24px rgba(0,0,0,.18)',
            'overflow:hidden',
            'min-width:260px',
            'max-width:320px',
        ].join(';');
        return el;
    }

    function openPopup(map, canvas, markerData, coords, accentColor, markerSize, autoPanDelay) {
        if (currentPopup) {
            map.removeChild(currentPopup);
            currentPopup = null;
            currentPopupContainer = null;
        }

        var offset = Math.round((markerSize || 40) / 2) + 16;

        var container = document.createElement('div');
        container.style.cssText = [
            'min-width:220px',
            'max-width:min(320px,calc(100vw - 24px))',
            'font-family:inherit',
            'display:flex',
            'flex-direction:column',
            'gap:2px',
            'margin-top:' + offset + 'px',
        ].join(';');

        var popup = new ymaps3.YMapMarker({ coordinates: coords }, container);
        map.addChild(popup);
        currentPopup = popup;
        currentPopupContainer = container;

        function autoPan() {
            if (!currentPopup || !canvas || !map.location) return;
            var cr = canvas.getBoundingClientRect();
            var pr = container.getBoundingClientRect();

            /* If overflows right, flip popup to open leftward */
            if (pr.right > cr.right - 8) {
                container.style.transform = 'translateX(-100%)';
                pr = container.getBoundingClientRect();
            }

            var dx = 0, dy = 0;
            if (pr.right  > cr.right  - 8) dx = pr.right  - cr.right  + 8;
            if (pr.bottom > cr.bottom - 8) dy = pr.bottom - cr.bottom + 8;
            if (pr.left   < cr.left   + 8) dx = pr.left   - cr.left   - 8;
            if (pr.top    < cr.top    + 8) dy = pr.top    - cr.top    - 8;
            if (dx === 0 && dy === 0) return;
            var z   = map.location.zoom;
            var c   = map.location.center;
            var dpp = 360 / (256 * Math.pow(2, z));
            map.setLocation({
                center: [
                    c[0] + dx * dpp,
                    c[1] - dy * dpp * Math.cos(c[1] * Math.PI / 180),
                ],
                duration: 300,
            });
        }

        var key = cacheKey(markerData.termType, markerData.termId);
        var cached = getCached(key);

        if (cached) {
            /* Instant render from cache */
            renderPartners(container, cached, map);
            setTimeout(autoPan, autoPanDelay || 200);
            return;
        }

        /* Show loading pill while fetching */
        var loadPill = makePill();
        loadPill.style.cssText += ';display:flex;align-items:center;padding:10px 12px 10px 20px;';
        var loadText = document.createElement('span');
        loadText.style.cssText = 'color:#aaa;font-size:13px;';
        loadText.textContent = 'Loading…';
        loadPill.appendChild(loadText);
        container.appendChild(loadPill);
        setTimeout(autoPan, autoPanDelay || 200);

        fetchPartners(markerData.termType, markerData.termId)
            .then(function (partners) {
                renderPartners(container, partners, map);
                setTimeout(autoPan, autoPanDelay || 200);
            })
            .catch(function () {
                container.innerHTML = '';
                var errPill = makePill();
                errPill.style.cssText += ';padding:14px 20px;color:#c00;font-size:13px;';
                errPill.textContent = 'Failed to load partners.';
                container.appendChild(errPill);
            });
    }

    function renderPartners(container, partners, map) {
        container.innerHTML = '';

        if (!partners || !partners.length) {
            var emptyPill = makePill();
            emptyPill.style.cssText += ';padding:14px 20px;text-align:center;color:#aaa;font-size:13px;';
            emptyPill.textContent = 'No partners found.';
            container.appendChild(emptyPill);
            return;
        }

        var shown = partners.slice(0, 8);

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

            var pill = makePill();
            var inner = document.createElement('div');
            inner.className = 'author-info d-flex align-items-center';
            inner.style.cssText = 'position:relative;padding:4px 12px 4px 4px;';
            inner.innerHTML = imgTag
                + '<div class="avatar-info mt-0 overflow-hidden">'
                +   '<a href="' + href + '" class="hover-7 link-body label-u text-charcoal-blue d-block lh-0 text-truncate" target="_blank" rel="noopener">' + name + '</a>'
                +   (pos ? '<span class="body-s lh-0 text-neutral-500 d-block mt-1 text-truncate">' + pos + '</span>' : '')
                + '</div>';

            pill.appendChild(inner);
            container.appendChild(pill);
        });

        if (partners.length > 8) {
            var morePill = document.createElement('a');
            morePill.href = '/partners/';
            morePill.style.cssText = [
                'display:block',
                'background:#fff',
                'border-radius:50px',
                'box-shadow:0 4px 24px rgba(0,0,0,.18)',
                'padding:10px 20px',
                'text-align:center',
                'font-size:12px',
                'color:#c8a96e',
                'text-decoration:none',
            ].join(';');
            morePill.textContent = 'All partners (' + partners.length + ')';
            container.appendChild(morePill);
        }

    }
})();
