import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { useEffect, useRef, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import apiFetch from '@wordpress/api-fetch';
import {
	PanelBody,
	SelectControl,
	RangeControl,
	ToggleControl,
	TextControl,
	TextareaControl,
	ColorPicker,
} from '@wordpress/components';

export default function Edit({ attributes, setAttributes }) {
	const {
		dataSource,
		height,
		zoom,
		centerLat,
		centerLng,
		mapType,
		scrollZoom,
		autoFitBounds,
		markerColor,
		markerSize,
		markerShape,
		markerShowCount,
		markerShowLabel,
		markerLabelSize,
		markerLabelColor,
		clustererEnabled,
		sidebarEnabled,
		sidebarPosition,
		sidebarTitle,
		sidebarColsSm,
		sidebarColsMd,
		sidebarColsLg,
		styleJson,
		zoomControl,
		routeButton,
		zoomMin,
		zoomMax,
	} = attributes;

	const mapRef        = useRef(null);
	const mapInstanceRef = useRef(null);
	const markersRef    = useRef([]);
	const [mapData, setMapData] = useState(null);

	const blockProps = useBlockProps({ className: 'horizons-partners-map-editor' });

	/* Fetch markers + sidebar from REST */
	useEffect(() => {
		apiFetch({ path: '/horizons/v1/partners-map-data?dataSource=' + dataSource })
			.then((data) => setMapData(data))
			.catch(() => {});
	}, [dataSource]);

	/* Init / reinit map */
	useEffect(() => {
		const el = mapRef.current;
		if (!el) return;

		function destroyMap() {
			markersRef.current.forEach((m) => {
				try { mapInstanceRef.current && mapInstanceRef.current.removeChild(m); } catch (e) {}
			});
			markersRef.current = [];
			if (mapInstanceRef.current) {
				try { mapInstanceRef.current.destroy(); } catch (e) {}
				mapInstanceRef.current = null;
			}
		}

		function buildMap() {
			if (typeof window.ymaps3 === 'undefined') return;
			destroyMap();

			const typeId = mapType === 'satellite' ? 'satellite' : mapType === 'hybrid' ? 'hybrid' : 'normal';
			const schemeOptions = { theme: typeId };
			if (styleJson) {
				try { schemeOptions.customization = JSON.parse(styleJson); } catch (e) {}
			}

			const map = new window.ymaps3.YMap(el, {
				location: { center: [centerLng, centerLat], zoom },
				behaviors: [],
			});
			map.addChild(new window.ymaps3.YMapDefaultSchemeLayer(schemeOptions));
			map.addChild(new window.ymaps3.YMapDefaultFeaturesLayer());
			mapInstanceRef.current = map;

			/* Place markers */
			const markers = (mapData && mapData.markers) || [];
			const size     = markerSize      || 40;
			const color    = markerColor     || '#C8A96E';
			const shape    = markerShape     || 'circle';
			const labelSz  = markerLabelSize || 11;
			const labelClr = markerLabelColor || '#1a1a1a';

			markers.forEach((m) => {
				const wrap = document.createElement('div');
				wrap.style.cssText = 'display:flex;align-items:center;gap:7px;pointer-events:none;';

				const dot = document.createElement('div');
				dot.style.cssText = [
					'width:' + size + 'px',
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
					'box-shadow:0 2px 8px rgba(0,0,0,.25)',
				].join(';');
				if (markerShowCount) dot.textContent = m.count;
				wrap.appendChild(dot);

				if (markerShowLabel) {
					const lbl = document.createElement('span');
					lbl.textContent = m.title;
					lbl.style.cssText = 'font-size:' + labelSz + 'px;font-weight:800;text-transform:uppercase;letter-spacing:.04em;color:' + labelClr + ';white-space:nowrap;';
					wrap.appendChild(lbl);
				}

				const marker = new window.ymaps3.YMapMarker({ coordinates: [m.lng, m.lat] }, wrap);
				map.addChild(marker);
				markersRef.current.push(marker);
			});

			/* Auto fit */
			if (markers.length > 1) {
				const lngs = markers.map((m) => m.lng);
				const lats = markers.map((m) => m.lat);
				map.setLocation({
					bounds: [
						[Math.min(...lngs), Math.min(...lats)],
						[Math.max(...lngs), Math.max(...lats)],
					],
					duration: 0,
				});
			}
		}

		if (typeof window.ymaps3 !== 'undefined') {
			window.ymaps3.ready.then(buildMap);
		} else {
			const iv = setInterval(() => {
				if (typeof window.ymaps3 !== 'undefined') {
					clearInterval(iv);
					window.ymaps3.ready.then(buildMap);
				}
			}, 300);
			return () => { clearInterval(iv); destroyMap(); };
		}

		return destroyMap;
	}, [centerLat, centerLng, zoom, mapType, styleJson, height,
		markerColor, markerSize, markerShape, markerShowCount, markerShowLabel, markerLabelSize, markerLabelColor,
		mapData]);

	const sidebar      = (mapData && mapData.sidebar) || [];
	const totalCount   = (mapData && mapData.total)   || 0;

	return (
		<>
			<InspectorControls>

				<PanelBody title={__('Map Settings', 'horizons')} initialOpen>
					<SelectControl
						label={__('Data source', 'horizons')}
						value={dataSource}
						options={[
							{ label: __('Regions and Countries', 'horizons'), value: 'both' },
							{ label: __('Regions only', 'horizons'),           value: 'regions' },
							{ label: __('Countries only', 'horizons'),         value: 'countries' },
						]}
						onChange={(val) => setAttributes({ dataSource: val })}
					/>
					<SelectControl
						label={__('Map type', 'horizons')}
						value={mapType}
						options={[
							{ label: __('Normal', 'horizons'),    value: 'normal' },
							{ label: __('Satellite', 'horizons'), value: 'satellite' },
							{ label: __('Hybrid', 'horizons'),    value: 'hybrid' },
						]}
						onChange={(val) => setAttributes({ mapType: val })}
					/>
					<RangeControl
						label={__('Map height (px)', 'horizons')}
						value={height}
						min={300}
						max={900}
						step={50}
						onChange={(val) => setAttributes({ height: val })}
					/>
					<RangeControl
						label={__('Initial zoom', 'horizons')}
						value={zoom}
						min={1}
						max={19}
						onChange={(val) => setAttributes({ zoom: val })}
					/>
					<TextControl
						label={__('Center latitude', 'horizons')}
						type="number"
						value={centerLat}
						step="0.0001"
						onChange={(val) => setAttributes({ centerLat: parseFloat(val) || 0 })}
					/>
					<TextControl
						label={__('Center longitude', 'horizons')}
						type="number"
						value={centerLng}
						step="0.0001"
						onChange={(val) => setAttributes({ centerLng: parseFloat(val) || 0 })}
					/>
					<ToggleControl
						label={__('Scroll zoom', 'horizons')}
						checked={scrollZoom}
						onChange={(val) => setAttributes({ scrollZoom: val })}
					/>
					<ToggleControl
						label={__('Auto fit bounds', 'horizons')}
						checked={autoFitBounds}
						onChange={(val) => setAttributes({ autoFitBounds: val })}
					/>
					<RangeControl
						label={__('Min zoom', 'horizons')}
						value={zoomMin}
						min={1}
						max={10}
						onChange={(val) => setAttributes({ zoomMin: val })}
					/>
					<RangeControl
						label={__('Max zoom', 'horizons')}
						value={zoomMax}
						min={5}
						max={19}
						onChange={(val) => setAttributes({ zoomMax: val })}
					/>
					<ToggleControl
						label={__('Zoom control buttons', 'horizons')}
						checked={zoomControl}
						onChange={(val) => setAttributes({ zoomControl: val })}
					/>
					<ToggleControl
						label={__('Route button in popup', 'horizons')}
						checked={routeButton}
						onChange={(val) => setAttributes({ routeButton: val })}
					/>
				</PanelBody>

				<PanelBody title={__('Markers', 'horizons')} initialOpen={false}>
					<p style={{ marginBottom: 8, fontSize: 12, color: '#555' }}>{__('Marker color', 'horizons')}</p>
					<ColorPicker
						color={markerColor}
						onChange={(val) => setAttributes({ markerColor: val })}
						enableAlpha={false}
					/>
					<RangeControl
						label={__('Marker size (px)', 'horizons')}
						value={markerSize}
						min={1}
						max={80}
						step={1}
						onChange={(val) => setAttributes({ markerSize: val })}
					/>
					<SelectControl
						label={__('Marker shape', 'horizons')}
						value={markerShape}
						options={[
							{ label: __('Circle', 'horizons'), value: 'circle' },
							{ label: __('Square', 'horizons'), value: 'square' },
						]}
						onChange={(val) => setAttributes({ markerShape: val })}
					/>
					<ToggleControl
						label={__('Show count', 'horizons')}
						checked={markerShowCount}
						onChange={(val) => setAttributes({ markerShowCount: val })}
					/>
					<ToggleControl
						label={__('Show country label', 'horizons')}
						checked={markerShowLabel}
						onChange={(val) => setAttributes({ markerShowLabel: val })}
					/>
					{markerShowLabel && (
						<>
							<RangeControl
								label={__('Label font size (px)', 'horizons')}
								value={markerLabelSize}
								min={1}
								max={24}
								step={1}
								onChange={(val) => setAttributes({ markerLabelSize: val })}
							/>
							<p style={{ marginTop: 12, marginBottom: 6, fontSize: 12, color: '#555' }}>
								{__('Label color', 'horizons')}
							</p>
							<ColorPicker
								color={markerLabelColor}
								onChange={(val) => setAttributes({ markerLabelColor: val })}
								enableAlpha={false}
							/>
						</>
					)}
					<ToggleControl
						label={__('Enable clusterer', 'horizons')}
						checked={clustererEnabled}
						onChange={(val) => setAttributes({ clustererEnabled: val })}
					/>
				</PanelBody>

				<PanelBody title={__('Sidebar', 'horizons')} initialOpen={false}>
					<ToggleControl
						label={__('Show sidebar', 'horizons')}
						checked={sidebarEnabled}
						onChange={(val) => setAttributes({ sidebarEnabled: val })}
					/>
					{sidebarEnabled && (
						<>
							<SelectControl
								label={__('Sidebar position', 'horizons')}
								value={sidebarPosition}
								options={[
									{ label: __('Left', 'horizons'),  value: 'left' },
									{ label: __('Right', 'horizons'), value: 'right' },
								]}
								onChange={(val) => setAttributes({ sidebarPosition: val })}
							/>
							<TextControl
								label={__('Sidebar title', 'horizons')}
								value={sidebarTitle}
								placeholder={__('e.g. Our partners', 'horizons')}
								onChange={(val) => setAttributes({ sidebarTitle: val })}
							/>
							<RangeControl
								label={__('Columns (base)', 'horizons')}
								value={sidebarColsSm}
								min={1}
								max={5}
								onChange={(val) => setAttributes({ sidebarColsSm: val })}
							/>
							<RangeControl
								label={__('Columns (≥768px)', 'horizons')}
								value={sidebarColsMd}
								min={1}
								max={5}
								onChange={(val) => setAttributes({ sidebarColsMd: val })}
							/>
							<RangeControl
								label={__('Columns (≥1024px)', 'horizons')}
								value={sidebarColsLg}
								min={1}
								max={5}
								onChange={(val) => setAttributes({ sidebarColsLg: val })}
							/>
						</>
					)}
				</PanelBody>

				<PanelBody title={__('Custom map style (JSON)', 'horizons')} initialOpen={false}>
					<TextareaControl
						value={styleJson}
						rows={8}
						placeholder={'[\n  { "tags": { "any": ["road"] }, "elements": "geometry", "stylers": [{ "color": "#f0ede8" }] }\n]'}
						onChange={(val) => setAttributes({ styleJson: val })}
					/>
					{styleJson && (() => {
						try { JSON.parse(styleJson); return null; } catch (e) {
							return <p style={{ color: '#c00', fontSize: 12 }}>⚠ {__('Invalid JSON', 'horizons')}</p>;
						}
					})()}
				</PanelBody>

			</InspectorControls>

			<div {...blockProps}>
				<div
					className={'horizons-partners-map' + (!sidebarEnabled ? ' horizons-partners-map--no-sidebar' : '') + (sidebarPosition === 'right' ? ' horizons-partners-map--sidebar-right' : '')}
					style={{ height: height + 'px', display: 'flex', borderRadius: '8px', overflow: 'hidden', boxShadow: '0 2px 16px rgba(0,0,0,.12)' }}
				>
					{sidebarEnabled && (
						<aside className="horizons-partners-map__sidebar">
							{sidebarTitle && (
								<div className="horizons-partners-map__sidebar-title">{sidebarTitle}</div>
							)}
							<div className="horizons-partners-map__sidebar-inner">
								<button className="horizons-partners-map__filter-btn is-active" disabled>
									{__('All partners', 'horizons')}
								</button>
								{sidebar.map((item) => item.type === 'region' ? (
									<button key={item.term_id} className="horizons-partners-map__filter-btn horizons-partners-map__filter-region" disabled>
										{item.name}
									</button>
								) : (
									<div key={item.term_id} className="horizons-partners-map__country-group">
										<button className="horizons-partners-map__filter-btn horizons-partners-map__filter-country" disabled>
											{item.name}
										</button>
										{item.regions && item.regions.length > 0 && (
											<div className="horizons-partners-map__regions">
												{item.regions.map((r) => (
													<button key={r.term_id} className="horizons-partners-map__filter-btn horizons-partners-map__filter-region" disabled>
														{r.name}
													</button>
												))}
											</div>
										)}
									</div>
								))}
							</div>
						</aside>
					)}
					<div
						ref={mapRef}
						className="horizons-partners-map__canvas"
						style={{ flex: 1, minWidth: 0, position: 'relative' }}
					/>
				</div>
			</div>
		</>
	);
}
