/**
 * GridControl - универсальный компонент для настройки адаптивных сеток
 *
 * Включает:
 * - Адаптивные колонки (row-cols-*)
 * - Адаптивные gap (g-*, gx-*, gy-*)
 * - Адаптивные spacing (p-*, m-*)
 *
 * @package Horizons Theme
 */

import { __ } from '@wordpress/i18n';
import { PanelBody, SelectControl, ToggleControl, ButtonGroup, Button } from '@wordpress/components';
import { ResponsiveControl, createBreakpointsConfig } from '../responsive-control';
import { getGapClasses, getRowColsClasses } from './helpers';

/**
 * GridControl Component
 *
 * @param {Object} props
 * @param {Object} props.attributes - Атрибуты блока
 * @param {Function} props.setAttributes - Функция setAttributes
 * @param {string} props.attributePrefix - Префикс атрибутов (например, 'grid', 'columns')
 * @param {boolean} props.showRowCols - Показывать настройки row-cols (по умолчанию true)
 * @param {boolean} props.showGap - Показывать настройки gap (по умолчанию true)
 * @param {boolean} props.showSpacing - Показывать настройки spacing (по умолчанию false)
 * @param {string} props.rowColsLabel - Кастомный label для row-cols
 * @param {string} props.gapLabel - Кастомный label для gap
 * @param {string} props.spacingLabel - Кастомный label для spacing
 */
export const GridControl = ({
	attributes,
	setAttributes,
	attributePrefix = 'grid',
	showRowCols = true,
	showGap = true,
	showSpacing = false,
	rowColsLabel,
	gapLabel,
	spacingLabel,
}) => {
	// Получаем атрибуты с учетом префикса
	const getAttr = (suffix) => attributes[`${attributePrefix}${suffix}`];

	const gapType = getAttr('GapType') || 'general';
	const spacingType = getAttr('SpacingType') || 'padding';

	// Генерируем классы gap для отображения
	// Собираем все классы для всех заполненных breakpoints
	const gapClasses = getGapClasses(attributes, attributePrefix);
	const gapClassesString = gapClasses.length > 0 ? gapClasses.join(' ') : __('No Gap Classes', 'horizons');

	// Генерируем классы row-cols для отображения
	const rowColsClasses = getRowColsClasses(attributes, attributePrefix);
	const rowColsClassesString = rowColsClasses.length > 0 ? rowColsClasses.join(' ') : __('No Row Cols Classes', 'horizons');

	return (
		<>
			{/* Row Cols Settings */}
			{showRowCols && (
				<div style={{ marginBottom: '16px' }}>
					{/* Отображение классов row-cols - над ResponsiveControl */}
					<div style={{
						marginBottom: '16px',
						padding: '8px 12px',
						backgroundColor: '#f0f0f1',
						borderRadius: '4px',
						fontSize: '12px',
						fontFamily: 'monospace',
						color: '#1e1e1e'
					}}>
						<div style={{
							marginBottom: '4px',
							fontSize: '11px',
							fontWeight: '500',
							textTransform: 'uppercase',
							color: '#757575'
						}}>
							{__('Row Cols Classes', 'horizons')}:
						</div>
						<div style={{ wordBreak: 'break-word' }}>
							{rowColsClassesString}
						</div>
					</div>

					<ResponsiveControl
						{...createBreakpointsConfig({
							type: 'columns',
							attributes,
							attributePrefix: `${attributePrefix}RowCols`,
							onChange: setAttributes,
							variant: 'dropdown',
							label: rowColsLabel || __('Columns Per Row', 'horizons'),
							tooltip: __('Number of columns at each breakpoint', 'horizons'),
						})}
					/>
				</div>
			)}

			{/* Gap Settings */}
			{showGap && (
				<div>
					<div style={{ marginBottom: '16px' }}>
						<div style={{ marginBottom: '8px', fontSize: '11px', fontWeight: '500', textTransform: 'uppercase', color: '#1e1e1e' }}>
							{__('Gap Type', 'horizons')}
						</div>
						<ButtonGroup style={{ display: 'flex', width: '100%' }}>
							<Button
								variant={gapType === 'general' ? 'primary' : 'secondary'}
								onClick={() => setAttributes({ [`${attributePrefix}GapType`]: 'general' })}
								style={{ flex: '1 1 auto' }}
							>
								{__('Gap', 'horizons')}
							</Button>
							<Button
								variant={gapType === 'x' ? 'primary' : 'secondary'}
								onClick={() => setAttributes({ [`${attributePrefix}GapType`]: 'x' })}
								style={{ flex: '1 1 auto' }}
							>
								{__('Gap-X', 'horizons')}
							</Button>
							<Button
								variant={gapType === 'y' ? 'primary' : 'secondary'}
								onClick={() => setAttributes({ [`${attributePrefix}GapType`]: 'y' })}
								style={{ flex: '1 1 auto' }}
							>
								{__('Gap-Y', 'horizons')}
							</Button>
						</ButtonGroup>
					</div>

					{/* Отображение классов gap - все типы одновременно */}
					<div style={{
						marginBottom: '16px',
						padding: '8px 12px',
						backgroundColor: '#f0f0f1',
						borderRadius: '4px',
						fontSize: '12px',
						fontFamily: 'monospace',
						color: '#1e1e1e'
					}}>
						<div style={{
							marginBottom: '4px',
							fontSize: '11px',
							fontWeight: '500',
							textTransform: 'uppercase',
							color: '#757575'
						}}>
							{__('Gap Classes', 'horizons')}:
						</div>
						<div style={{ wordBreak: 'break-word' }}>
							{gapClassesString}
						</div>
					</div>

					{/* ResponsiveControl для Gap (General) */}
					{gapType === 'general' && (
						<ResponsiveControl
							{...createBreakpointsConfig({
								type: 'custom',
								attributes,
								attributePrefix: `${attributePrefix}Gap`,
								onChange: setAttributes,
								variant: 'dropdown',
								label: __('Gap Size', 'horizons'),
								tooltip: __('Spacing between grid items (both axes)', 'horizons'),
								customOptions: {
									default: ['', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10'],
									xs: ['', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10'],
									sm: ['', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10'],
									md: ['', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10'],
									lg: ['', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10'],
									xl: ['', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10'],
									xxl: ['', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10'],
								},
							})}
						/>
					)}

					{/* ResponsiveControl для Gap-X (Horizontal) */}
					{gapType === 'x' && (
						<ResponsiveControl
							{...createBreakpointsConfig({
								type: 'custom',
								attributes,
								attributePrefix: `${attributePrefix}GapX`,
								onChange: setAttributes,
								variant: 'dropdown',
								label: __('Gap-X Size', 'horizons'),
								tooltip: __('Horizontal spacing between grid items', 'horizons'),
								customOptions: {
									default: ['', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10'],
									xs: ['', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10'],
									sm: ['', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10'],
									md: ['', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10'],
									lg: ['', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10'],
									xl: ['', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10'],
									xxl: ['', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10'],
								},
							})}
						/>
					)}

					{/* ResponsiveControl для Gap-Y (Vertical) */}
					{gapType === 'y' && (
						<ResponsiveControl
							{...createBreakpointsConfig({
								type: 'custom',
								attributes,
								attributePrefix: `${attributePrefix}GapY`,
								onChange: setAttributes,
								variant: 'dropdown',
								label: __('Gap-Y Size', 'horizons'),
								tooltip: __('Vertical spacing between grid items', 'horizons'),
								customOptions: {
									default: ['', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10'],
									xs: ['', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10'],
									sm: ['', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10'],
									md: ['', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10'],
									lg: ['', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10'],
									xl: ['', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10'],
									xxl: ['', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10'],
								},
							})}
						/>
					)}
				</div>
			)}

			{/* Spacing Settings */}
			{showSpacing && (
				<div>
					<SelectControl
						label={__('Spacing Type', 'horizons')}
						value={spacingType}
						options={[
							{ label: __('Padding', 'horizons'), value: 'padding' },
							{ label: __('Margin', 'horizons'), value: 'margin' },
						]}
						onChange={(value) => setAttributes({ [`${attributePrefix}SpacingType`]: value })}
						help={__('Choose between padding (inside) or margin (outside)', 'horizons')}
					/>

					<ResponsiveControl
						{...createBreakpointsConfig({
							type: 'custom',
							attributes,
							attributePrefix: `${attributePrefix}Spacing`,
							onChange: setAttributes,
							variant: 'dropdown',
							label: __('Spacing Size', 'horizons'),
							tooltip: __('Container padding or margin', 'horizons'),
							customOptions: {
								default: ['0', '1', '2', '3', '4', '5', '6'],
								xs: ['', '0', '1', '2', '3', '4', '5'],
								sm: ['', '0', '1', '2', '3', '4', '5'],
								md: ['', '0', '1', '2', '3', '4', '5', '6'],
								lg: ['', '0', '1', '2', '3', '4', '5', '6'],
								xl: ['', '0', '1', '2', '3', '4', '5', '6'],
								xxl: ['', '0', '1', '2', '3', '4', '5', '6'],
							},
						})}
					/>
				</div>
			)}
		</>
	);
};

