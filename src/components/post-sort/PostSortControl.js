/**
 * PostSortControl - компонент для сортировки постов
 * 
 * @package Horizons Theme
 */

import { __ } from '@wordpress/i18n';
import { SelectControl } from '@wordpress/components';

/**
 * PostSortControl Component
 * 
 * @param {Object} props
 * @param {string} props.orderBy - Текущее значение сортировки (orderby)
 * @param {string} props.order - Текущее направление сортировки (order)
 * @param {Function} props.onOrderByChange - Callback при изменении orderby
 * @param {Function} props.onOrderChange - Callback при изменении order
 */
export const PostSortControl = ({
	orderBy = 'date',
	order = 'desc',
	onOrderByChange,
	onOrderChange,
}) => {
	const orderByOptions = [
		{ label: __('Date', 'horizons'), value: 'date' },
		{ label: __('Title', 'horizons'), value: 'title' },
		{ label: __('Modified Date', 'horizons'), value: 'modified' },
		{ label: __('Comment Count', 'horizons'), value: 'comment_count' },
		{ label: __('Random', 'horizons'), value: 'rand' },
		{ label: __('ID', 'horizons'), value: 'id' },
		{ label: __('Author', 'horizons'), value: 'author' },
		{ label: __('Menu Order', 'horizons'), value: 'menu_order' },
	];

	const orderOptions = [
		{ label: __('Descending', 'horizons'), value: 'desc' },
		{ label: __('Ascending', 'horizons'), value: 'asc' },
	];

	return (
		<>
			<SelectControl
				label={__('Sort By', 'horizons')}
				value={orderBy}
				options={orderByOptions}
				onChange={onOrderByChange}
				help={__('Select how to sort the posts', 'horizons')}
			/>
			
			<div style={{ marginTop: '16px' }}>
				<SelectControl
					label={__('Order', 'horizons')}
					value={order}
					options={orderOptions}
					onChange={onOrderChange}
					help={__('Select sort direction', 'horizons')}
				/>
			</div>
		</>
	);
};










