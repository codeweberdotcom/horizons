import { __ } from '@wordpress/i18n';
import { SelectControl } from '@wordpress/components';
import { useEffect } from '@wordpress/element';

const POST_TEMPLATES = [
	{
		value: 'default',
		label: __('Default', 'horizons'),
		description: __('Simple layout with figure overlay and post header/footer', 'horizons'),
	},
	{
		value: 'card',
		label: __('Card', 'horizons'),
		description: __('Card layout with shadow and card body', 'horizons'),
	},
	{
		value: 'card-content',
		label: __('Card Content', 'horizons'),
		description: __('Card with excerpt and footer', 'horizons'),
	},
	{
		value: 'slider',
		label: __('Slider', 'horizons'),
		description: __('Slider layout with category on image and excerpt', 'horizons'),
	},
	{
		value: 'default-clickable',
		label: __('Default Clickable', 'horizons'),
		description: __('Fully clickable card with lift effect', 'horizons'),
	},
	{
		value: 'overlay-5',
		label: __('Overlay 5', 'horizons'),
		description: __('Overlay effect with 90% opacity and bottom overlay for date', 'horizons'),
	},
];

const CLIENT_TEMPLATES = [
	{
		value: 'client-simple',
		label: __('Client Simple', 'horizons'),
		description: __('Simple logo for Swiper slider', 'horizons'),
	},
	{
		value: 'client-grid',
		label: __('Client Grid', 'horizons'),
		description: __('Logo in figure with adaptive padding for Grid layout', 'horizons'),
	},
	{
		value: 'client-card',
		label: __('Client Card', 'horizons'),
		description: __('Logo in card with shadow for Grid with cards', 'horizons'),
	},
];

const TESTIMONIAL_TEMPLATES = [
	{
		value: 'default',
		label: __('Default', 'horizons'),
		description: __('Basic testimonial card with rating, text, avatar and author', 'horizons'),
	},
	{
		value: 'card',
		label: __('Card', 'horizons'),
		description: __('Card with colored backgrounds (Sandbox style)', 'horizons'),
	},
	{
		value: 'blockquote',
		label: __('Blockquote', 'horizons'),
		description: __('Block with quote and icon', 'horizons'),
	},
	{
		value: 'icon',
		label: __('Icon', 'horizons'),
		description: __('Simple blockquote with icon, without rating', 'horizons'),
	},
];

const DOCUMENT_TEMPLATES = [
	{
		value: 'document-card',
		label: __('Document Card', 'horizons'),
		description: __('Card layout with email button for documents', 'horizons'),
	},
	{
		value: 'document-card-download',
		label: __('Document Card Download', 'horizons'),
		description: __('Card layout with AJAX download button for documents', 'horizons'),
	},
];

const FAQ_TEMPLATES = [
	{
		value: 'default',
		label: __('Default', 'horizons'),
		description: __('FAQ card with icon, question and answer', 'horizons'),
	},
];

const STAFF_TEMPLATES = [
	{
		value: 'default',
		label: __('Default', 'horizons'),
		description: __('Basic staff card with image, name and position', 'horizons'),
	},
	{
		value: 'card',
		label: __('Card', 'horizons'),
		description: __('Card with colored backgrounds (Sandbox style)', 'horizons'),
	},
	{
		value: 'circle',
		label: __('Circle', 'horizons'),
		description: __('Circular avatar with social links', 'horizons'),
	},
	{
		value: 'circle_center',
		label: __('Circle Center', 'horizons'),
		description: __('Circular avatar centered with social links', 'horizons'),
	},
	{
		value: 'circle_center_alt',
		label: __('Circle Center Alt', 'horizons'),
		description: __('Circular avatar centered with link on image and social links', 'horizons'),
	},
];

export const PostGridTemplateControl = ({ value, onChange, postType = 'post' }) => {
	// Определяем какие шаблоны показывать в зависимости от типа записи
	let templates;
	let defaultTemplate;
	
	if (postType === 'clients') {
		templates = CLIENT_TEMPLATES;
		defaultTemplate = 'client-simple';
	} else if (postType === 'testimonials') {
		templates = TESTIMONIAL_TEMPLATES;
		defaultTemplate = 'default';
	} else if (postType === 'documents') {
		templates = DOCUMENT_TEMPLATES;
		defaultTemplate = 'document-card';
	} else if (postType === 'faq') {
		templates = FAQ_TEMPLATES;
		defaultTemplate = 'default';
	} else if (postType === 'staff') {
		templates = STAFF_TEMPLATES;
		defaultTemplate = 'default';
	} else {
		templates = POST_TEMPLATES;
		defaultTemplate = 'default';
	}
	
	const selectedTemplate = templates.find(t => t.value === value) || templates[0];
	
	// Если шаблон только один, автоматически устанавливаем его при первой загрузке
	useEffect(() => {
		if (templates.length === 1 && value !== templates[0].value) {
			onChange(templates[0].value);
		}
		// eslint-disable-next-line react-hooks/exhaustive-deps
	}, [templates.length, value]);
	
	// Если шаблон только один, скрываем SelectControl и показываем только информацию
	if (templates.length === 1) {
		return (
			<>
				<div style={{ marginBottom: '8px' }}>
					<strong>{__('Template', 'horizons')}:</strong> {selectedTemplate.label}
				</div>
				<p style={{ margin: 0, fontSize: '12px', color: '#757575' }}>
					{selectedTemplate.description}
				</p>
			</>
		);
	}

	return (
		<>
			<SelectControl
				label={__('Template', 'horizons')}
				value={value || defaultTemplate}
				options={templates.map(template => ({
					label: template.label,
					value: template.value,
				}))}
				onChange={(newValue) => onChange(newValue)}
				help={selectedTemplate.description}
			/>
		</>
	);
};

