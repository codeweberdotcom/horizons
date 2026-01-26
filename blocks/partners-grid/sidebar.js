import { InspectorControls } from '@wordpress/block-editor';
import { TabPanel, PanelBody, TextControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { MainControl } from './controls/MainControl';
import { LayoutControl } from './controls/LayoutControl';

export const PartnersGridSidebar = ({ attributes, setAttributes }) => {
	const tabs = [
		{ name: 'main', title: __('Main', 'horizons') },
		{ name: 'layout', title: __('Layout', 'horizons') },
		{ name: 'settings', title: __('Settings', 'horizons') },
	];

	return (
		<InspectorControls>
			<TabPanel tabs={tabs}>
				{(tab) => (
					<>
						{tab.name === 'main' && (
							<PanelBody>
								<MainControl attributes={attributes} setAttributes={setAttributes} />
							</PanelBody>
						)}
						{tab.name === 'layout' && (
							<PanelBody>
								<LayoutControl attributes={attributes} setAttributes={setAttributes} />
							</PanelBody>
						)}
						{tab.name === 'settings' && (
							<PanelBody>
								<TextControl
									label={__('Block Class', 'horizons')}
									value={attributes.blockClass || ''}
									onChange={(value) => setAttributes({ blockClass: value })}
									help={__('Additional CSS class for the block wrapper', 'horizons')}
								/>
								<TextControl
									label={__('Block ID', 'horizons')}
									value={attributes.blockId || ''}
									onChange={(value) => setAttributes({ blockId: value })}
									help={__('Custom ID for the block wrapper', 'horizons')}
								/>
								<TextControl
									label={__('Block Data', 'horizons')}
									value={attributes.blockData || ''}
									onChange={(value) => setAttributes({ blockData: value })}
									help={__('Custom data attribute value', 'horizons')}
								/>
							</PanelBody>
						)}
					</>
				)}
			</TabPanel>
		</InspectorControls>
	);
};
