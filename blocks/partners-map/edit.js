import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import { PanelBody, SelectControl, RangeControl } from '@wordpress/components';

export default function Edit({ attributes, setAttributes }) {
	const { dataSource, height, zoom } = attributes;
	const blockProps = useBlockProps({ className: 'horizons-partners-map-editor' });

	return (
		<>
			<InspectorControls>
				<PanelBody title={__('Map Settings', 'horizons')}>
					<SelectControl
						label={__('Show', 'horizons')}
						value={dataSource}
						options={[
							{ label: __('Regions and Countries', 'horizons'), value: 'both' },
							{ label: __('Regions only', 'horizons'), value: 'regions' },
							{ label: __('Countries only', 'horizons'), value: 'countries' },
						]}
						onChange={(val) => setAttributes({ dataSource: val })}
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
						max={12}
						onChange={(val) => setAttributes({ zoom: val })}
					/>
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
						{__('Shows partner regions and countries on Yandex Map with sidebar filter. Configure in the panel on the right.', 'horizons')}
					</p>
					<p style={{ margin: '12px 0 0', color: '#aaa', fontSize: '12px' }}>
						{__('Height', 'horizons')}: {height}px &nbsp;·&nbsp;
						{__('Zoom', 'horizons')}: {zoom} &nbsp;·&nbsp;
						{dataSource === 'both'
							? __('Regions + Countries', 'horizons')
							: dataSource === 'regions'
							? __('Regions only', 'horizons')
							: __('Countries only', 'horizons')}
					</p>
				</div>
			</div>
		</>
	);
}
