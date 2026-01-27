import { __ } from '@wordpress/i18n';
import { RangeControl } from '@wordpress/components';

export const LayoutControl = ({ attributes, setAttributes }) => {
	const { gridColumns, gridColumnsMd, gridColumnsSm } = attributes;

	return (
		<>
			<div style={{ marginTop: '16px' }}>
				<RangeControl 
					label={__('Columns (lg)', 'horizons')} 
					value={parseInt(gridColumns) || 4} 
					onChange={(value) => setAttributes({ gridColumns: String(value) })} 
					min={1} 
					max={12} 
				/>
			</div>
			<div style={{ marginTop: '16px' }}>
				<RangeControl 
					label={__('Columns (md)', 'horizons')} 
					value={parseInt(gridColumnsMd) || 4} 
					onChange={(value) => setAttributes({ gridColumnsMd: String(value) })} 
					min={1} 
					max={12} 
				/>
			</div>
			<div style={{ marginTop: '16px' }}>
				<RangeControl 
					label={__('Columns (sm)', 'horizons')} 
					value={parseInt(gridColumnsSm) || 2} 
					onChange={(value) => setAttributes({ gridColumnsSm: String(value) })} 
					min={1} 
					max={12} 
				/>
			</div>
		</>
	);
};
