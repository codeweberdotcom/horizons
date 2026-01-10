import { __ } from '@wordpress/i18n';
import { SelectControl, RangeControl, ToggleControl } from '@wordpress/components';

const animationOptions = [
    { value: '', label: __('None', 'horizons') },
    { value: 'fadeIn', label: __('Fade In', 'horizons') },
    { value: 'slideInLeft', label: __('Slide In Left', 'horizons') },
    { value: 'slideInRight', label: __('Slide In Right', 'horizons') },
    { value: 'slideInDown', label: __('Slide In Down', 'horizons') },
    { value: 'slideInUp', label: __('Slide In Up', 'horizons') },
    { value: 'zoomIn', label: __('Zoom In', 'horizons') },
    { value: 'zoomOut', label: __('Zoom Out', 'horizons') },
    { value: 'rotateIn', label: __('Rotate In', 'horizons') },
    { value: 'bounceIn', label: __('Bounce In', 'horizons') },
    { value: 'bounceInLeft', label: __('Bounce In Left', 'horizons') },
    { value: 'bounceInRight', label: __('Bounce In Right', 'horizons') },
    { value: 'bounceInDown', label: __('Bounce In Down', 'horizons') },
    { value: 'bounceInUp', label: __('Bounce In Up', 'horizons') },
    { value: 'flipInX', label: __('Flip In X', 'horizons') },
    { value: 'flipInY', label: __('Flip In Y', 'horizons') },
];

export const AnimationControl = ({ attributes, setAttributes }) => {
    const {
        animationEnabled,
        animationType,
        animationDuration,
        animationDelay,
    } = attributes;

    return (
        <div>
            <ToggleControl
                label={__('Enable Animation', 'horizons')}
                checked={animationEnabled}
                onChange={(value) => setAttributes({ animationEnabled: value })}
                help={animationEnabled ? __('Scroll in editor to preview animation', 'horizons') : ''}
            />
            {animationEnabled && (
                <>
                    <SelectControl
                        label={__('Animation Type', 'horizons')}
                        value={animationType}
                        options={animationOptions}
                        onChange={(value) => setAttributes({ animationType: value })}
                    />
                    <RangeControl
                        label={__('Duration (ms)', 'horizons')}
                        value={animationDuration}
                        onChange={(value) => setAttributes({ animationDuration: value })}
                        min={0}
                        max={5000}
                        step={100}
                    />
                    <RangeControl
                        label={__('Delay (ms)', 'horizons')}
                        value={animationDelay}
                        onChange={(value) => setAttributes({ animationDelay: value })}
                        min={0}
                        max={5000}
                        step={100}
                    />
                </>
            )}
        </div>
    );
};