import { useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import { useEffect, useState } from '@wordpress/element';
import { PartnersGridSidebar } from './sidebar';
import apiFetch from '@wordpress/api-fetch';

export default function Edit({ attributes, setAttributes, clientId }) {
	const { postsPerPage = 3, orderBy = 'date', order = 'ASC', gridColumns = '3', gridColumnsMd = '3', gridGap = '3', blockClass = '' } = attributes;
	const [posts, setPosts] = useState([]);
	const [isLoading, setIsLoading] = useState(false);
	const blockProps = useBlockProps({ className: `horizons-partners-grid-block ${blockClass}`, 'data-block': clientId });
	
	useEffect(() => {
		const fetchPosts = async () => {
			setIsLoading(true);
			try {
				const perPage = postsPerPage || 3;
				// REST API требует order в нижнем регистре (asc/desc)
				const orderLower = (order || 'ASC').toLowerCase();
				const apiPath = `/wp/v2/partners?per_page=${perPage}&orderby=${orderBy}&order=${orderLower}&_embed`;
				
				const fetchedPosts = await apiFetch({ path: apiPath });
				
				if (!Array.isArray(fetchedPosts)) {
					setPosts([]);
					setIsLoading(false);
					return;
				}
				
				// Преобразуем посты для отображения
				const postsData = fetchedPosts.map((post) => {
					// Получаем метаданные из REST API
					const position = post.meta?._partners_position || '';
					const name = post.meta?._partners_name || '';
					const surname = post.meta?._partners_surname || '';
					const fullPosition = post.meta?._partners_full_position || '';
					
					// Получаем изображение
					let thumbnail = '';
					if (post.featured_media) {
						thumbnail = post._embedded?.['wp:featuredmedia']?.[0]?.source_url || '';
					}
					
					const fullName = name && surname ? `${name} ${surname.toUpperCase()}` : post.title?.rendered || '';
					
					return {
						id: post.id,
						title: post.title?.rendered || '',
						link: post.link || '#',
						position,
						name,
						surname,
						fullPosition,
						thumbnail,
						fullName,
					};
				});
				
				setPosts(postsData);
			} catch (error) {
				console.error('Partners Grid: Error fetching posts:', error);
				setPosts([]);
			} finally {
				setIsLoading(false);
			}
		};
		fetchPosts();
	}, [postsPerPage, orderBy, order]);
	
	const getColClasses = () => {
		const colClasses = [];
		const colsDefault = parseInt(gridColumns) || 3;
		const colsMd = parseInt(gridColumnsMd) || 3;
		
		colClasses.push(`col-md-${12 / colsDefault}`);
		if (colsMd !== colsDefault) {
			colClasses.push(`col-md-${12 / colsMd}`);
		}
		
		return colClasses.join(' ');
	};
	
	const gapClass = `g-${gridGap} g-md-${gridGap}`;
	const colClass = getColClasses();
	
	return (
		<>
			<PartnersGridSidebar attributes={attributes} setAttributes={setAttributes} />
			<div {...blockProps}>
				{isLoading ? (
					<div className="horizons-partners-grid-placeholder" style={{ padding: '20px', textAlign: 'center' }}>
						{__('Loading partners...', 'horizons')}
					</div>
				) : posts.length === 0 ? (
					<div className="horizons-partners-grid-placeholder" style={{ padding: '20px', textAlign: 'center' }}>
						{__('No partners found. Please ensure partners post type exists and has posts.', 'horizons')}
					</div>
				) : (
					<div className={`row ${gapClass}`}>
						{posts.map((post) => (
							<div key={post.id} className={colClass}>
								<div className="swiper-slide">
									<figure className="lift" style={{ marginBottom: '1rem' }}>
										{post.thumbnail ? (
											<img src={post.thumbnail} alt={post.fullName} style={{ width: '100%', height: 'auto' }} />
										) : (
											<div style={{ width: '100%', height: '200px', background: '#f0f0f0', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
												<span>{__('No image', 'horizons')}</span>
											</div>
										)}
										<div className="caption-wrapper-1 p-7" style={{ position: 'relative' }}>
											<div className="caption bg-matte-color mt-auto label-u text-neutral-50 px-4 py-2" style={{ display: 'inline-block' }}>
												{post.position || __('Position', 'horizons')}
											</div>
										</div>
									</figure>
									<div className="team-item-content text-dark mt-4">
										<h3 className="h4">{post.fullName || post.title}</h3>
										{post.fullPosition && (
											<div className="label-u">{post.fullPosition}</div>
										)}
									</div>
								</div>
							</div>
						))}
					</div>
				)}
			</div>
		</>
	);
}
