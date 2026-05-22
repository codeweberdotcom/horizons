import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import {
	PanelBody,
	SelectControl,
	RangeControl,
	ToggleControl,
	TextControl,
	TextareaControl,
	ColorPicker,
	__experimentalNumberControl as NumberControl,
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
		clustererEnabled,
		sidebarEnabled,
		sidebarPosition,
		sidebarTitle,
		styleJson,
	} = attributes;

	const blockProps = useBlockProps({ className: 'horizons-partners-map-editor' });

	const mapTypeLabel =
		mapType === 'satellite' ? __('Satellite', 'horizons') :
		mapType === 'hybrid'    ? __('Hybrid', 'horizons') :
		__('Normal', 'horizons');

	return (
		<>
			<InspectorControls>

				{/* ── Map Settings ── */}
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
						help={__('Allow zooming the map with mouse scroll', 'horizons')}
						checked={scrollZoom}
						onChange={(val) => setAttributes({ scrollZoom: val })}
					/>
					<ToggleControl
						label={__('Auto fit bounds', 'horizons')}
						help={__('Automatically zoom to show all markers on load', 'horizons')}
						checked={autoFitBounds}
						onChange={(val) => setAttributes({ autoFitBounds: val })}
					/>
				</PanelBody>

				{/* ── Markers ── */}
				<PanelBody title={__('Markers', 'horizons')} initialOpen={false}>
					<p style={{ marginBottom: 8, fontSize: 12, color: '#555' }}>
						{__('Marker color', 'horizons')}
					</p>
					<ColorPicker
						color={markerColor}
						onChange={(val) => setAttributes({ markerColor: val })}
						enableAlpha={false}
					/>
					<RangeControl
						label={__('Marker size (px)', 'horizons')}
						value={markerSize}
						min={20}
						max={80}
						step={4}
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
						help={__('Display partner count number on marker', 'horizons')}
						checked={markerShowCount}
						onChange={(val) => setAttributes({ markerShowCount: val })}
					/>
					<ToggleControl
						label={__('Show country label', 'horizons')}
						help={__('Display country/region name next to marker (uppercase bold)', 'horizons')}
						checked={markerShowLabel}
						onChange={(val) => setAttributes({ markerShowLabel: val })}
					/>
					<ToggleControl
						label={__('Enable clusterer', 'horizons')}
						help={__('Group nearby markers into clusters', 'horizons')}
						checked={clustererEnabled}
						onChange={(val) => setAttributes({ clustererEnabled: val })}
					/>
				</PanelBody>

				{/* ── Sidebar ── */}
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
						</>
					)}
				</PanelBody>

				{/* ── Style JSON ── */}
				<PanelBody title={__('Custom map style (JSON)', 'horizons')} initialOpen={false}>
					<p style={{ marginBottom: 8, fontSize: 12, color: '#555' }}>
						{__('Paste Yandex Maps v3 customization JSON array here.', 'horizons')}
					</p>
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
				<div style={{
					padding: '40px 20px',
					background: '#f7f6f5',
					textAlign: 'center',
					border: '2px dashed #ccc',
					borderRadius: '8px',
				}}>
					<div style={{ fontSize: '36px', marginBottom: '8px' }}>📍</div>
					<p style={{ margin: '0 0 4px', fontWeight: '600', fontSize: '15px' }}>
						{__('Partners Map', 'horizons')}
					</p>
					<p style={{ margin: 0, color: '#888', fontSize: '13px' }}>
						{__('Yandex Map with partner locations. Configure in the sidebar panel.', 'horizons')}
					</p>
					<p style={{ margin: '12px 0 0', color: '#aaa', fontSize: '12px' }}>
						{height}px · {__('zoom', 'horizons')} {zoom} · {mapTypeLabel}
						{sidebarEnabled ? ' · ' + __('sidebar', 'horizons') + ' ' + sidebarPosition : ''}
						{clustererEnabled ? ' · ' + __('clusterer', 'horizons') : ''}
					</p>
				</div>
			</div>
		</>
	);
}
