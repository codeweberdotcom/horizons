import { __ } from '@wordpress/i18n';
import { RangeControl, ToggleControl, SelectControl } from '@wordpress/components';

export const MainControl = ({ attributes, setAttributes }) => {
	const { termsPerPage, orderBy, order, useAltTitle, showAllPracticeLink } = attributes;

	const orderByOptions = [
		{ label: __('Meta Value (Order)', 'horizons'), value: 'meta_value_num' },
		{ label: __('Name', 'horizons'), value: 'name' },
		{ label: __('Count', 'horizons'), value: 'count' },
		{ label: __('Slug', 'horizons'), value: 'slug' },
	];

	const orderOptions = [
		{ label: __('Ascending', 'horizons'), value: 'ASC' },
		{ label: __('Descending', 'horizons'), value: 'DESC' },
	];

	return (
		<>
			<RangeControl
				label={__('Terms Per Page', 'horizons')}
				value={termsPerPage === -1 ? 0 : termsPerPage}
				onChange={(value) => setAttributes({ termsPerPage: value === 0 ? -1 : value })}
				min={0}
				max={50}
				initialPosition={-1}
			/>
			<div style={{ marginTop: '16px' }}>
				<SelectControl label={__('Order By', 'horizons')} value={orderBy || 'meta_value_num'} options={orderByOptions} onChange={(value) => setAttributes({ orderBy: value })} />
			</div>
			<div style={{ marginTop: '16px' }}>
				<SelectControl label={__('Order', 'horizons')} value={order || 'ASC'} options={orderOptions} onChange={(value) => setAttributes({ order: value })} />
			</div>
			<div style={{ marginTop: '16px' }}>
				<ToggleControl label={__('Use Alternative Title', 'horizons')} checked={useAltTitle !== false} onChange={(value) => setAttributes({ useAltTitle: value })} />
			</div>
			<div style={{ marginTop: '16px' }}>
				<ToggleControl label={__('Show "All Practice" Link', 'horizons')} checked={showAllPracticeLink !== false} onChange={(value) => setAttributes({ showAllPracticeLink: value })} />
			</div>
		</>
	);
};
