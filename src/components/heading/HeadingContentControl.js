import { __ } from '@wordpress/i18n';
import { ToggleControl, BaseControl } from '@wordpress/components';
import { RichText } from '@wordpress/block-editor';
import { createHeadingTagOptions, createSubtitleTagOptions } from '../../blocks/heading-subtitle/utils';

export const HeadingContentControl = ({ attributes, setAttributes, hideSubtitle = false, hideText = false, hideTitle = false }) => {
    const {
        enableTitle,
        enableSubtitle,
        enableText,
        title,
        subtitle,
        text,
        order,
        titleTag,
        subtitleTag,
        subtitleLine,
    } = attributes;

    return (
        <>
            {!hideTitle && (
                <ToggleControl
                    label={__('Enable Title', 'horizons')}
                    checked={enableTitle}
                    onChange={(value) => setAttributes({ enableTitle: value })}
                />
            )}
            {!hideSubtitle && (
                <>
                    <ToggleControl
                        label={__('Enable Subtitle', 'horizons')}
                        checked={enableSubtitle}
                        onChange={(value) => setAttributes({ enableSubtitle: value })}
                    />
                </>
            )}
            {!hideText && (
                <ToggleControl
                    label={__('Enable Paragraph', 'horizons')}
                    checked={enableText}
                    onChange={(value) => setAttributes({ enableText: value })}
                />
            )}
            {!hideSubtitle && (
                <>
                    <ToggleControl
                        label={__('Subtitle First', 'horizons')}
                        checked={order === 'subtitle-first'}
                        onChange={(value) => setAttributes({ order: value ? 'subtitle-first' : 'title-first' })}
                    />
                    <ToggleControl
                        label={__('Subtitle Line', 'horizons')}
                        checked={subtitleLine}
                        onChange={(value) => setAttributes({ subtitleLine: value })}
                    />
                </>
            )}
            {enableTitle && (
                <BaseControl
                    label={__('Title Text', 'horizons')}
                    className="mb-3"
                >
                    <div style={{
                        border: '1px solid #ccc',
                        borderRadius: '4px',
                        padding: '8px',
                        minHeight: '40px',
                        backgroundColor: '#fff',
                        fontSize: '13px'
                    }}>
                        <RichText
                            tagName="div"
                            value={title}
                            onChange={(value) => setAttributes({ title: value })}
                            placeholder={__('Enter title...', 'horizons')}
                            allowedFormats={[]}
                            __unstableAllowHtml={true}
                            withoutInteractiveFormatting
                        />
                    </div>
                </BaseControl>
            )}
            {!hideSubtitle && enableSubtitle && (
                <BaseControl
                    label={__('Subtitle Text', 'horizons')}
                    className="mb-3"
                >
                    <div style={{
                        border: '1px solid #ccc',
                        borderRadius: '4px',
                        padding: '8px',
                        minHeight: '40px',
                        backgroundColor: '#fff',
                        fontSize: '13px'
                    }}>
                        <RichText
                            tagName="div"
                            value={subtitle}
                            onChange={(value) => setAttributes({ subtitle: value })}
                            placeholder={__('Enter subtitle...', 'horizons')}
                            allowedFormats={[]}
                            __unstableAllowHtml={true}
                        />
                    </div>
                </BaseControl>
            )}
            {enableText && (
                <BaseControl
                    label={__('Paragraph Text', 'horizons')}
                    className="mb-3"
                >
                    <div style={{
                        border: '1px solid #ccc',
                        borderRadius: '4px',
                        padding: '8px',
                        minHeight: '80px',
                        backgroundColor: '#fff',
                        fontSize: '13px'
                    }}>
                        <RichText
                            tagName="div"
                            value={text}
                            onChange={(value) => setAttributes({ text: value })}
                            placeholder={__('Enter paragraph...', 'horizons')}
                            allowedFormats={[]}
                            __unstableAllowHtml={true}
                        />
                    </div>
                </BaseControl>
            )}
        </>
    );
};

