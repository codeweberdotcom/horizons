/**
 * Border Radius Utilities
 * 
 * @package Horizons Theme
 */

import { __ } from '@wordpress/i18n';

/**
 * Border Radius Options
 * Based on Bootstrap 5 and theme custom classes
 * 
 * Bootstrap 5: rounded-0 to rounded-5
 * Theme custom: rounded-xl (0.8rem)
 * Theme variables:
 * $border-radius: 0.4rem
 * $border-radius-sm: 0.2rem
 * $border-radius-xl: 0.8rem
 * $rounded-pill: 1.5rem
 */
export const borderRadiusOptions = [
	{ value: '', label: __('Default', 'horizons') },
	{ value: 'rounded-0', label: __('Rounded-0 (0)', 'horizons') },
	{ value: 'rounded-1', label: __('Rounded-1 (0.2rem)', 'horizons') },
	{ value: 'rounded-2', label: __('Rounded-2 (0.25rem)', 'horizons') },
	{ value: 'rounded', label: __('Rounded (0.4rem)', 'horizons') },
	{ value: 'rounded-3', label: __('Rounded-3 (0.5rem)', 'horizons') },
	{ value: 'rounded-4', label: __('Rounded-4 (1rem)', 'horizons') },
	{ value: 'rounded-5', label: __('Rounded-5 (1.5rem)', 'horizons') },
	{ value: 'rounded-xl', label: __('Rounded-xl (0.8rem - Theme)', 'horizons') },
	{ value: 'rounded-pill', label: __('Rounded-pill (50rem)', 'horizons') },
	{ value: 'rounded-circle', label: __('Rounded-circle (50%)', 'horizons') },
];

/**
 * Positional Border Radius Options
 */
export const positionOptions = [
	{ value: '', label: __('All Sides', 'horizons') },
	{ value: 'top', label: __('Top Only', 'horizons') },
	{ value: 'bottom', label: __('Bottom Only', 'horizons') },
	{ value: 'start', label: __('Start (Left) Only', 'horizons') },
	{ value: 'end', label: __('End (Right) Only', 'horizons') },
];

/**
 * Generate border radius class
 * 
 * @param {string} radius - Border radius value
 * @param {string} position - Position (top, bottom, start, end)
 * @returns {string} Generated class
 */
export const generateBorderRadiusClass = (radius = '', position = '') => {
	if (!radius) return '';
	
	if (position && position !== '') {
		// rounded-top, rounded-xl-bottom, etc.
		const baseClass = radius === 'rounded' ? 'rounded' : radius;
		return `${baseClass}-${position}`;
	}
	
	return radius;
};

