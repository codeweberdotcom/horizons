import { __ } from '@wordpress/i18n';
import { RangeControl } from '@wordpress/components';

export const LayoutControl = ({ attributes, setAttributes }) => {
	const { gridColumns, gridColumnsMd, gridGap } = attributes;

	return (
		<>
			<div style={{ marginTop: '16px' }}>
				<RangeControl 
					label={__('Columns (default)', 'horizons')} 
					value={parseInt(gridColumns) || 3} 
					onChange={(value) => setAttributes({ gridColumns: String(value) })} 
					min={1} 
					max={12} 
				/>
			</div>
			<div style={{ marginTop: '16px' }}>
				<RangeControl 
					label={__('Columns (md)', 'horizons')} 
					value={parseInt(gridColumnsMd) || 3} 
					onChange={(value) => setAttributes({ gridColumnsMd: String(value) })} 
					min={1} 
					max={12} 
				/>
			</div>
			<div style={{ marginTop: '16px' }}>
				<RangeControl 
					label={__('Gap', 'horizons')} 
					value={parseInt(gridGap) || 3} 
					onChange={(value) => setAttributes({ gridGap: String(value) })} 
					min={0} 
					max={5} 
				/>
			</div>
		</>
	);
};
