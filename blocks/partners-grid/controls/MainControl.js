import { __ } from '@wordpress/i18n';
import { RangeControl, SelectControl } from '@wordpress/components';

export const MainControl = ({ attributes, setAttributes }) => {
	const { postsPerPage, orderBy, order } = attributes;

	const orderByOptions = [
		{ label: __('Date', 'horizons'), value: 'date' },
		{ label: __('Title', 'horizons'), value: 'title' },
		{ label: __('Menu Order', 'horizons'), value: 'menu_order' },
	];

	const orderOptions = [
		{ label: __('Ascending', 'horizons'), value: 'ASC' },
		{ label: __('Descending', 'horizons'), value: 'DESC' },
	];

	return (
		<>
			<RangeControl
				label={__('Posts Per Page', 'horizons')}
				value={postsPerPage || 3}
				onChange={(value) => setAttributes({ postsPerPage: value })}
				min={1}
				max={20}
			/>
			<div style={{ marginTop: '16px' }}>
				<SelectControl 
					label={__('Order By', 'horizons')} 
					value={orderBy || 'date'} 
					options={orderByOptions} 
					onChange={(value) => setAttributes({ orderBy: value })} 
				/>
			</div>
			<div style={{ marginTop: '16px' }}>
				<SelectControl 
					label={__('Order', 'horizons')} 
					value={order || 'ASC'} 
					options={orderOptions} 
					onChange={(value) => setAttributes({ order: value })} 
				/>
			</div>
		</>
	);
};
