import { useState, useEffect } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { PanelBody, SelectControl, ToggleControl, ComboboxControl, ButtonGroup, Button, TextControl } from '@wordpress/components';
import { ColorTypeControl } from '../colors/ColorTypeControl';
import { TagControl } from '../tag';
import { colors } from '../../utilities/colors';
import {
	createSizeOptions,
	createWeightOptions,
	createTransformOptions,
	createLeadOptions,
} from '../../blocks/heading-subtitle/utils';

export const HeadingTypographyControl = ({ attributes, setAttributes, hideSubtitle = false, hideText = false }) => {
    const {
        titleTag,
        subtitleTag,
        textTag,
        titleColor,
        titleColorType,
        subtitleColor,
        subtitleColorType,
        textColor,
        textColorType,
        titleSize,
        subtitleSize,
        textSize,
        titleWeight,
        subtitleWeight,
        textWeight,
        titleTransform,
        subtitleTransform,
        textTransform,
        titleClass,
        subtitleClass,
        textClass,
        titleLine,
        subtitleLine,
        lead,
    } = attributes;

    const [activeTab, setActiveTab] = useState('title');

    // Если paragraph скрыт и активна вкладка paragraph, переключаемся на title
    useEffect(() => {
        if (hideText && activeTab === 'paragraph') {
            setActiveTab('title');
        }
    }, [hideText, activeTab]);

    return (
        <div style={{ padding: '16px' }}>
            <ButtonGroup>
                <Button
                    isPrimary={activeTab === 'title'}
                    onClick={() => setActiveTab('title')}
                >
                    {__('Title', 'horizons')}
                </Button>
                {!hideSubtitle && (
                    <Button
                        isPrimary={activeTab === 'subtitle'}
                        onClick={() => setActiveTab('subtitle')}
                    >
                        {__('Subtitle', 'horizons')}
                    </Button>
                )}
                {!hideText && (
                    <Button
                        isPrimary={activeTab === 'paragraph'}
                        onClick={() => setActiveTab('paragraph')}
                    >
                        {__('Paragraph', 'horizons')}
                    </Button>
                )}
            </ButtonGroup>
            {activeTab === 'title' && (
                <>
                    <TagControl
                        label={__('Title Tag', 'horizons')}
                        value={titleTag}
                        onChange={(value) => setAttributes({ titleTag: value })}
                        type="heading"
                    />
                    <ColorTypeControl
                        label={__('Title Color Type', 'horizons')}
                        value={titleColorType}
                        onChange={(value) => setAttributes({ titleColorType: value })}
                        options={[
                            { value: 'solid', label: __('Solid', 'horizons') },
                            { value: 'soft', label: __('Soft', 'horizons') },
                            { value: 'pale', label: __('Pale', 'horizons') },
                        ]}
                    />
                    <ComboboxControl
                        label={__('Title Color', 'horizons')}
                        value={titleColor}
                        options={colors}
                        onChange={(value) => setAttributes({ titleColor: value })}
                    />
                    <SelectControl
                        label={__('Title Size', 'horizons')}
                        value={titleSize}
                        options={createSizeOptions()}
                        onChange={(value) => setAttributes({ titleSize: value })}
                    />
                    <SelectControl
                        label={__('Title Weight', 'horizons')}
                        value={titleWeight}
                        options={createWeightOptions()}
                        onChange={(value) => setAttributes({ titleWeight: value })}
                    />
                    <SelectControl
                        label={__('Title Transform', 'horizons')}
                        value={titleTransform}
                        options={createTransformOptions()}
                        onChange={(value) => setAttributes({ titleTransform: value })}
                    />
                    <TextControl
                        label={__('Title Class', 'horizons')}
                        value={titleClass}
                        onChange={(value) => setAttributes({ titleClass: value })}
                        placeholder="mb-4 custom-class"
                        help={__('Additional CSS classes', 'horizons')}
                    />
                </>
            )}
            {activeTab === 'subtitle' && (
                <>
                    <TagControl
                        label={__('Subtitle Tag', 'horizons')}
                        value={subtitleTag}
                        onChange={(value) => setAttributes({ subtitleTag: value })}
                        type="subtitle"
                    />
                    <ColorTypeControl
                        label={__('Subtitle Color Type', 'horizons')}
                        value={subtitleColorType}
                        onChange={(value) => setAttributes({ subtitleColorType: value })}
                        options={[
                            { value: 'solid', label: __('Solid', 'horizons') },
                            { value: 'soft', label: __('Soft', 'horizons') },
                            { value: 'pale', label: __('Pale', 'horizons') },
                        ]}
                    />
                    <ComboboxControl
                        label={__('Subtitle Color', 'horizons')}
                        value={subtitleColor}
                        options={colors}
                        onChange={(value) => setAttributes({ subtitleColor: value })}
                    />
                    <SelectControl
                        label={__('Subtitle Size', 'horizons')}
                        value={subtitleSize}
                        options={createSizeOptions()}
                        onChange={(value) => setAttributes({ subtitleSize: value })}
                    />
                    <SelectControl
                        label={__('Subtitle Weight', 'horizons')}
                        value={subtitleWeight}
                        options={createWeightOptions()}
                        onChange={(value) => setAttributes({ subtitleWeight: value })}
                    />
                    <SelectControl
                        label={__('Subtitle Transform', 'horizons')}
                        value={subtitleTransform}
                        options={createTransformOptions()}
                        onChange={(value) => setAttributes({ subtitleTransform: value })}
                    />
                    <SelectControl
                        label={__('Lead Style', 'horizons')}
                        value={lead}
                        options={createLeadOptions()}
                        onChange={(value) => setAttributes({ lead: value })}
                    />
					<TextControl
                        label={__('Subtitle Class', 'horizons')}
                        value={subtitleClass}
                        onChange={(value) => setAttributes({ subtitleClass: value })}
                        placeholder="mb-4 custom-class"
                        help={__('Additional CSS classes', 'horizons')}
                    />
                </>
            )}
            {!hideText && activeTab === 'paragraph' && (
                <>
                    <TagControl
                        label={__('Paragraph Tag', 'horizons')}
                        value={textTag}
                        onChange={(value) => setAttributes({ textTag: value })}
                        type="text"
                    />
                    <ColorTypeControl
                        label={__('Paragraph Color Type', 'horizons')}
                        value={textColorType}
                        onChange={(value) => setAttributes({ textColorType: value })}
                        options={[
                            { value: 'solid', label: __('Solid', 'horizons') },
                            { value: 'soft', label: __('Soft', 'horizons') },
                            { value: 'pale', label: __('Pale', 'horizons') },
                        ]}
                    />
                    <ComboboxControl
                        label={__('Paragraph Color', 'horizons')}
                        value={textColor}
                        options={colors}
                        onChange={(value) => setAttributes({ textColor: value })}
                    />
                    <SelectControl
                        label={__('Paragraph Size', 'horizons')}
                        value={textSize}
                        options={createSizeOptions()}
                        onChange={(value) => setAttributes({ textSize: value })}
                    />
                    <SelectControl
                        label={__('Paragraph Weight', 'horizons')}
                        value={textWeight}
                        options={createWeightOptions()}
                        onChange={(value) => setAttributes({ textWeight: value })}
                    />
                    <SelectControl
                        label={__('Paragraph Transform', 'horizons')}
                        value={textTransform}
                        options={createTransformOptions()}
                        onChange={(value) => setAttributes({ textTransform: value })}
                    />
                    <TextControl
                        label={__('Paragraph Class', 'horizons')}
                        value={textClass}
                        onChange={(value) => setAttributes({ textClass: value })}
                        placeholder="mb-4 custom-class"
                        help={__('Additional CSS classes', 'horizons')}
                    />
                </>
            )}
        </div>
    );
};

