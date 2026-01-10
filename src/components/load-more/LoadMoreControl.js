/**
 * LoadMoreControl Component
 * 
 * Universal control for "Load More" / "Show More" functionality
 * Manages settings for AJAX-based content loading
 * 
 * @package Horizons Theme
 */

import { __ } from '@wordpress/i18n';
import { ToggleControl, RangeControl, SelectControl, Button } from '@wordpress/components';

export const LoadMoreControl = ({ attributes, setAttributes, attributePrefix = 'loadMore' }) => {
	const getAttr = (suffix) => attributes[`${attributePrefix}${suffix}`];
	
	const enableLoadMore = getAttr('Enable') || false;
	const initialCount = getAttr('InitialCount') || 6;
	const loadMoreCount = getAttr('LoadMoreCount') || 6;
	const loadMoreText = getAttr('Text') || 'show-more';
	const loadMoreType = getAttr('Type') || 'button';
	const loadMoreButtonSize = getAttr('ButtonSize') || '';
	const loadMoreButtonStyle = getAttr('ButtonStyle') || 'solid';

	// Предустановленные тексты для кнопки/ссылки
	const textOptions = [
		{ label: __('Show More', 'horizons'), value: 'show-more' },
		{ label: __('Load More', 'horizons'), value: 'load-more' },
		{ label: __('Show More Items', 'horizons'), value: 'show-more-items' },
		{ label: __('More Posts', 'horizons'), value: 'more-posts' },
		{ label: __('View All', 'horizons'), value: 'view-all' },
		{ label: __('Show All', 'horizons'), value: 'show-all' },
	];

	// Типы элементов
	const typeOptions = [
		{ label: __('Button', 'horizons'), value: 'button' },
		{ label: __('Link', 'horizons'), value: 'link' },
	];

	// Получаем текст по значению
	const getTextByValue = (value) => {
		const option = textOptions.find(opt => opt.value === value);
		return option ? option.label : textOptions[0].label;
	};

	return (
		<>
			<RangeControl
				label={__('How many items to display', 'horizons')}
				value={initialCount}
				onChange={(value) => setAttributes({ [`${attributePrefix}InitialCount`]: value })}
				min={1}
				max={50}
				help={__('Number of items displayed initially', 'horizons')}
			/>

			<ToggleControl
				label={__('Enable "Show More" button', 'horizons')}
				checked={enableLoadMore}
				onChange={(value) => setAttributes({ [`${attributePrefix}Enable`]: value })}
			/>

			{enableLoadMore && (
				<>
					<SelectControl
						label={__('Element Type', 'horizons')}
						value={loadMoreType}
						options={typeOptions}
						onChange={(value) => setAttributes({ [`${attributePrefix}Type`]: value })}
						help={__('Select element type: button or link', 'horizons')}
					/>

					<SelectControl
						label={__('Button/Link Text', 'horizons')}
						value={loadMoreText}
						options={textOptions}
						onChange={(value) => setAttributes({ [`${attributePrefix}Text`]: value })}
						help={__('Select text for button or link', 'horizons')}
					/>

					<RangeControl
						label={__('How many items to load', 'horizons')}
						value={loadMoreCount}
						onChange={(value) => setAttributes({ [`${attributePrefix}LoadMoreCount`]: value })}
						min={1}
						max={50}
						help={__('Number of items loaded when clicking the button', 'horizons')}
					/>

					{loadMoreType === 'button' && (
						<>
							{/* Размер кнопки */}
							<div style={{ marginTop: '16px' }}>
								<div style={{ marginBottom: '8px' }}>
									<label style={{ display: 'block', marginBottom: '8px' }}>
										{__('Button Size', 'horizons')}
									</label>
								</div>
								<div style={{ display: 'flex', gap: '4px', flexWrap: 'wrap' }}>
									{[
										{ label: 'ExSm', value: 'btn-xs' },
										{ label: 'Sm', value: 'btn-sm' },
										{ label: 'Md', value: '' },
										{ label: 'Lg', value: 'btn-lg' },
										{ label: 'ExLg', value: 'btn-elg' },
									].map((size) => (
										<Button
											key={size.value}
											isPrimary={loadMoreButtonSize === size.value}
											onClick={() => setAttributes({ [`${attributePrefix}ButtonSize`]: size.value })}
											isSmall
										>
											{size.label}
										</Button>
									))}
								</div>
							</div>

							{/* Стиль кнопки */}
							<div style={{ marginTop: '16px' }}>
								<div style={{ marginBottom: '8px' }}>
									<label style={{ display: 'block', marginBottom: '8px' }}>
										{__('Button Style', 'horizons')}
									</label>
								</div>
								<div style={{ display: 'flex', gap: '4px', flexWrap: 'wrap' }}>
									{[
										{ label: __('Solid', 'horizons'), value: 'solid' },
										{ label: __('Outline', 'horizons'), value: 'outline' },
									].map((style) => (
										<Button
											key={style.value}
											isPrimary={loadMoreButtonStyle === style.value}
											onClick={() => setAttributes({ [`${attributePrefix}ButtonStyle`]: style.value })}
											isSmall
										>
											{style.label}
										</Button>
									))}
								</div>
							</div>
						</>
					)}
				</>
			)}
		</>
	);
};



