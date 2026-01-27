import { __ } from '@wordpress/i18n';
import { RangeControl, SelectControl, ToggleControl, CheckboxControl, Spinner } from '@wordpress/components';
import { useState, useEffect } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';

export const MainControl = ({ attributes, setAttributes }) => {
	const { postsPerPage, orderBy, order, showAllAwardsLink, awardCategory = [], awardTags = [] } = attributes;
	const [categories, setCategories] = useState([]);
	const [tags, setTags] = useState([]);
	const [loadingTerms, setLoadingTerms] = useState(true);

	useEffect(() => {
		const fetchTerms = async () => {
			setLoadingTerms(true);
			try {
				const [cats, tgs] = await Promise.all([
					apiFetch({ path: '/wp/v2/award_category?per_page=100&_fields=id,name' }).catch(() => []),
					apiFetch({ path: '/wp/v2/award_tags?per_page=100&_fields=id,name' }).catch(() => []),
				]);
				setCategories(Array.isArray(cats) ? cats : []);
				setTags(Array.isArray(tgs) ? tgs : []);
			} catch (e) {
				setCategories([]);
				setTags([]);
			} finally {
				setLoadingTerms(false);
			}
		};
		fetchTerms();
	}, []);

	const toggleCategory = (termId) => {
		const next = awardCategory.includes(termId) ? awardCategory.filter((id) => id !== termId) : [...awardCategory, termId].sort((a, b) => a - b);
		setAttributes({ awardCategory: next });
	};

	const toggleTag = (termId) => {
		const next = awardTags.includes(termId) ? awardTags.filter((id) => id !== termId) : [...awardTags, termId].sort((a, b) => a - b);
		setAttributes({ awardTags: next });
	};

	const orderByOptions = [
		{ label: __('Date', 'horizons'), value: 'date' },
		{ label: __('Title', 'horizons'), value: 'title' },
		{ label: __('Menu Order', 'horizons'), value: 'menu_order' },
	];

	const orderOptions = [
		{ label: __('Ascending', 'horizons'), value: 'ASC' },
		{ label: __('Descending', 'horizons'), value: 'DESC' },
	];

	return (
		<>
			<RangeControl
				label={__('Posts Per Page', 'horizons')}
				value={postsPerPage || 7}
				onChange={(value) => setAttributes({ postsPerPage: value })}
				min={1}
				max={20}
			/>
			<div style={{ marginTop: '16px' }}>
				<SelectControl 
					label={__('Order By', 'horizons')} 
					value={orderBy || 'date'} 
					options={orderByOptions} 
					onChange={(value) => setAttributes({ orderBy: value })} 
				/>
			</div>
			<div style={{ marginTop: '16px' }}>
				<SelectControl 
					label={__('Order', 'horizons')} 
					value={order || 'DESC'} 
					options={orderOptions} 
					onChange={(value) => setAttributes({ order: value })} 
				/>
			</div>
			<div style={{ marginTop: '16px' }}>
				<ToggleControl 
					label={__('Show "All Awards" Link', 'horizons')} 
					checked={showAllAwardsLink !== false} 
					onChange={(value) => setAttributes({ showAllAwardsLink: value })} 
				/>
			</div>
			{/* Filters by taxonomy */}
			<div style={{ marginTop: '20px', borderTop: '1px solid #ddd', paddingTop: '16px' }}>
				{loadingTerms ? (
					<Spinner />
				) : (
					<>
						{ categories.length > 0 && (
							<div style={{ marginBottom: '16px' }}>
								<strong style={{ display: 'block', marginBottom: '8px' }}>{__('Filter by Category', 'horizons')}</strong>
								{categories.map((t) => (
									<CheckboxControl
										key={t.id}
										label={t.name || `#${t.id}`}
										checked={(awardCategory || []).includes(t.id)}
										onChange={() => toggleCategory(t.id)}
									/>
								))}
							</div>
						) }
						{ tags.length > 0 && (
							<div>
								<strong style={{ display: 'block', marginBottom: '8px' }}>{__('Filter by Tags', 'horizons')}</strong>
								{tags.map((t) => (
									<CheckboxControl
										key={t.id}
										label={t.name || `#${t.id}`}
										checked={(awardTags || []).includes(t.id)}
										onChange={() => toggleTag(t.id)}
									/>
								))}
							</div>
						) }
					</>
				) }
			</div>
		</>
	);
};
