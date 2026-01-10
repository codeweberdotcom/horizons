/**
 * Shadow Utilities
 * 
 * @package Horizons Theme
 */

import { __ } from '@wordpress/i18n';

/**
 * Shadow Options
 * Based on Bootstrap 5 and theme custom shadows
 */
export const shadowOptions = [
	{ value: '', label: __('Default (with border)', 'horizons') },
	{ value: 'shadow-none', label: __('No Shadow', 'horizons') },
	{ value: 'shadow-sm', label: __('Small', 'horizons') },
	{ value: 'shadow', label: __('Regular', 'horizons') },
	{ value: 'shadow-lg', label: __('Large', 'horizons') },
	{ value: 'shadow-xl', label: __('Extra Large', 'horizons') },
];

/**
 * Generate shadow class
 * 
 * @param {string} shadow - Shadow value
 * @returns {string} Shadow class
 */
export const generateShadowClass = (shadow = '') => {
	return shadow || '';
};













