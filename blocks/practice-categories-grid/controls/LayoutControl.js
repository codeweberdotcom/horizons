import { __ } from '@wordpress/i18n';
import { ButtonGroup, Button, RangeControl } from '@wordpress/components';

const GRID_TYPE_OPTIONS = [
	{ value: 'classic', label: __('Classic grid', 'horizons') },
	{ value: 'columns-grid', label: __('Columns grid', 'horizons') },
];

const GridTypeControl = ({ value, onChange }) => (
	<div className="mb-3">
		<div className="component-sidebar-title">
			<label>{__('Grid type', 'horizons')}</label>
		</div>
		<ButtonGroup>
			{GRID_TYPE_OPTIONS.map((option) => (
				<Button key={option.value} isPrimary={value === option.value} onClick={() => onChange(option.value)}>
					{option.label}
				</Button>
			))}
		</ButtonGroup>
	</div>
);

export const LayoutControl = ({ attributes, setAttributes }) => {
	const { gridType, gridColumns, gridColumnsMd, gridRowCols, gridRowColsMd, gridGap, gridGapMd } = attributes;

	return (
		<>
			<GridTypeControl
				value={gridType || 'classic'}
				onChange={(value) => {
					if (value === 'columns-grid') {
						setAttributes({ gridType: value, gridColumns: '', gridColumnsMd: '', gridRowCols: attributes.gridRowCols || '12', gridRowColsMd: attributes.gridRowColsMd || '3' });
					} else if (value === 'classic') {
						setAttributes({ gridType: value, gridRowCols: '', gridRowColsMd: '', gridColumns: attributes.gridColumns || '3', gridColumnsMd: attributes.gridColumnsMd || '3' });
					}
				}}
			/>
			{gridType === 'classic' && (
				<>
					<div style={{ marginTop: '16px' }}>
						<RangeControl label={__('Columns (default)', 'horizons')} value={parseInt(gridColumns) || 3} onChange={(value) => setAttributes({ gridColumns: String(value) })} min={1} max={12} />
					</div>
					<div style={{ marginTop: '16px' }}>
						<RangeControl label={__('Columns (md)', 'horizons')} value={parseInt(gridColumnsMd) || 3} onChange={(value) => setAttributes({ gridColumnsMd: String(value) })} min={1} max={12} />
					</div>
					<div style={{ marginTop: '16px' }}>
						<RangeControl label={__('Gap', 'horizons')} value={parseInt(gridGap) || 3} onChange={(value) => setAttributes({ gridGap: String(value) })} min={0} max={5} />
					</div>
				</>
			)}
			{gridType === 'columns-grid' && (
				<>
					<div style={{ marginTop: '16px' }}>
						<RangeControl label={__('Columns Per Row (default)', 'horizons')} value={parseInt(gridRowCols) || 12} onChange={(value) => setAttributes({ gridRowCols: String(value) })} min={1} max={12} />
					</div>
					<div style={{ marginTop: '16px' }}>
						<RangeControl label={__('Columns Per Row (md)', 'horizons')} value={parseInt(gridRowColsMd) || 3} onChange={(value) => setAttributes({ gridRowColsMd: String(value) })} min={1} max={12} />
					</div>
					<div style={{ marginTop: '16px' }}>
						<RangeControl label={__('Gap', 'horizons')} value={parseInt(gridGap) || 3} onChange={(value) => setAttributes({ gridGap: String(value) })} min={0} max={5} />
					</div>
				</>
			)}
		</>
	);
};
