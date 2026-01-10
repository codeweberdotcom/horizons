import { __ } from '@wordpress/i18n';
import { ToggleControl, TextControl } from '@wordpress/components';

/**
 * LightboxControl Component
 * 
 * Universal control for lightbox settings
 * Can be used in any block that supports lightbox functionality
 * 
 * @package Horizons Theme
 */
export const LightboxControl = ({ attributes, setAttributes }) => {
	const { enableLightbox, lightboxGallery } = attributes;

	return (
		<>
			<ToggleControl
				label={__('Enable Lightbox', 'horizons')}
				checked={enableLightbox}
				onChange={(value) => setAttributes({ enableLightbox: value })}
			/>

			{enableLightbox && (
				<TextControl
					label={__('Gallery Name', 'horizons')}
					value={lightboxGallery}
					onChange={(value) => setAttributes({ lightboxGallery: value })}
				/>
			)}
		</>
	);
};










