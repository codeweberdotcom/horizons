import { useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import { useEffect, useState } from '@wordpress/element';
import { AwardsGridSidebar } from './sidebar';
import apiFetch from '@wordpress/api-fetch';

export default function Edit({ attributes, setAttributes, clientId }) {
	const { postsPerPage = 7, orderBy = 'date', order = 'DESC', gridColumns = '4', gridColumnsMd = '4', gridColumnsSm = '2', showAllAwardsLink = true, awardCategory = [], awardTags = [], blockClass = '' } = attributes;
	const [posts, setPosts] = useState([]);
	const [isLoading, setIsLoading] = useState(false);
	const blockProps = useBlockProps({ className: `horizons-awards-grid-block ${blockClass}`, 'data-block': clientId });
	
	useEffect(() => {
		const fetchPosts = async () => {
			setIsLoading(true);
			try {
				const perPage = postsPerPage || 7;
				const orderLower = (order || 'DESC').toLowerCase();
				let apiPath = `/wp/v2/awards?per_page=${perPage}&orderby=${orderBy}&order=${orderLower}&_embed`;
				if ((awardCategory || []).length) {
					apiPath += '&award_category=' + awardCategory.join(',');
				}
				if ((awardTags || []).length) {
					apiPath += '&award_tags=' + awardTags.join(',');
				}
				const fetchedPosts = await apiFetch({ path: apiPath });
				
				if (!Array.isArray(fetchedPosts)) {
					setPosts([]);
					setIsLoading(false);
					return;
				}
				
				// Преобразуем посты для отображения
				const postsData = fetchedPosts.map((post) => {
					// Получаем изображение
					let thumbnail = '';
					if (post.featured_media) {
						thumbnail = post._embedded?.['wp:featuredmedia']?.[0]?.source_url || '';
					}
					
					return {
						id: post.id,
						title: post.title?.rendered || '',
						link: post.link || '#',
						thumbnail,
					};
				});
				
				setPosts(postsData);
			} catch (error) {
				console.error('Awards Grid: Error fetching posts:', error);
				setPosts([]);
			} finally {
				setIsLoading(false);
			}
		};
		fetchPosts();
	}, [postsPerPage, orderBy, order, awardCategory, awardTags]);
	
	const getColumnClasses = () => {
		return `row row-cols-1 row-cols-sm-${gridColumnsSm} row-cols-md-${gridColumnsMd} row-cols-lg-${gridColumns} gx-3 gy-3`;
	};
	
	return (
		<>
			<AwardsGridSidebar attributes={attributes} setAttributes={setAttributes} />
			<div {...blockProps}>
				{isLoading ? (
					<div className="horizons-awards-grid-placeholder" style={{ padding: '20px', textAlign: 'center' }}>
						{__('Loading awards...', 'horizons')}
					</div>
				) : posts.length === 0 ? (
					<div className="horizons-awards-grid-placeholder" style={{ padding: '20px', textAlign: 'center' }}>
						{__('No awards found. Please ensure awards post type exists and has posts.', 'horizons')}
					</div>
				) : (
					<div className={getColumnClasses()}>
						{posts.map((post) => (
							<div key={post.id} className="col">
								<a href={post.link} className="card hover-scale h-100 align-items-center">
									<div className="card-body align-items-center d-flex p-0">
										<figure className="p-0 mb-0">
											{post.thumbnail ? (
												<img decoding="async" src={post.thumbnail} alt={post.title} style={{ width: '100%', height: 'auto' }} />
											) : (
												<div style={{ width: '100%', height: '200px', background: '#f0f0f0', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
													<span>{__('No image', 'horizons')}</span>
												</div>
											)}
										</figure>
									</div>
								</a>
							</div>
						))}
						{showAllAwardsLink && (
							<div className="col">
								<div className="card h-100 bg-dusty-navy">
									<div className="card-body py-15 py-md-5 align-content-center text-center">
										<span className="hover-4 link-body label-s text-sub-white">{__('All Awards', 'horizons')}</span>
									</div>
								</div>
							</div>
						)}
					</div>
				)}
			</div>
		</>
	);
}
