import { TextControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

/**
 * Универсальный компонент набора полей Block Class/Data/ID.
 * Можно передавать собственные подписи/подсказки через props.
 */
const normalizeId = (value = '') => value.replace(/^#/, '').trim();

export const BlockMetaFields = ({
	attributes,
	setAttributes,
	fieldKeys = {
		classKey: 'sectionClass',
		dataKey: 'sectionData',
		idKey: 'sectionId',
	},
	labels = {
		classLabel: __('Block Class', 'horizons'),
		dataLabel: __('Block Data', 'horizons'),
		idLabel: __('Block ID', 'horizons'),
	},
	placeholders = {
		classPlaceholder: __('custom-wrapper classes', 'horizons'),
		dataPlaceholder: __('key=value,key2=value2', 'horizons'),
		idPlaceholder: __('custom-id', 'horizons'),
	},
}) => {
	const handleChange = (key, value) => {
		let nextValue = value;
		if (key === fieldKeys.idKey) {
			nextValue = normalizeId(value);
		}
		setAttributes({ [key]: nextValue });
	};

	return (
		<>
			<TextControl
				label={labels.classLabel}
				value={attributes[fieldKeys.classKey] || ''}
				placeholder={placeholders.classPlaceholder}
				onChange={(value) => handleChange(fieldKeys.classKey, value)}
			/>
			<TextControl
				label={labels.dataLabel}
				value={attributes[fieldKeys.dataKey] || ''}
				placeholder={placeholders.dataPlaceholder}
				help={__('Comma separated pairs: key=value,key2=value2', 'horizons')}
				onChange={(value) => handleChange(fieldKeys.dataKey, value)}
			/>
			<TextControl
				label={labels.idLabel}
				value={attributes[fieldKeys.idKey] || ''}
				placeholder={placeholders.idPlaceholder}
				onChange={(value) => handleChange(fieldKeys.idKey, value)}
			/>
		</>
	);
};

export default BlockMetaFields;


