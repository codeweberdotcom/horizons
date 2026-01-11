import { useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import { useEffect, useState } from '@wordpress/element';
import { useSelect } from '@wordpress/data';
import { PracticeCategoriesGridSidebar } from './sidebar';
import apiFetch from '@wordpress/api-fetch';

	const getRowColsClasses = (attrs = {}, prefix = 'grid', fallbackCount = null) => {
	const rowCols = attrs[`${prefix}RowCols`] || '';
	const rowColsSm = attrs[`${prefix}RowColsSm`] || '';
	const rowColsMd = attrs[`${prefix}RowColsMd`] || '';
	const rowColsLg = attrs[`${prefix}RowColsLg`] || '';
	const rowColsXl = attrs[`${prefix}RowColsXl`] || '';
	const rowColsXxl = attrs[`${prefix}RowColsXxl`] || '';
	const classes = [];
	if (rowCols || fallbackCount) classes.push(`row-cols-${rowCols || fallbackCount}`);
	if (rowColsSm) classes.push(`row-cols-sm-${rowColsSm}`);
	if (rowColsMd) classes.push(`row-cols-md-${rowColsMd}`);
	if (rowColsLg) classes.push(`row-cols-lg-${rowColsLg}`);
	if (rowColsXl) classes.push(`row-cols-xl-${rowColsXl}`);
	if (rowColsXxl) classes.push(`row-cols-xxl-${rowColsXxl}`);
	return classes.filter(Boolean);
};

const getGapClasses = (attrs = {}, prefix = 'grid') => {
	const gapType = attrs[`${prefix}GapType`] || 'general';
	const gap = attrs[`${prefix}Gap`] || '';
	const gapMd = attrs[`${prefix}GapMd`] || '';
	const gapX = attrs[`${prefix}GapX`] || '';
	const gapXMd = attrs[`${prefix}GapXMd`] || '';
	const gapY = attrs[`${prefix}GapY`] || '';
	const gapYMd = attrs[`${prefix}GapYMd`] || '';
	const classes = [];
	if (gapType === 'general' || gapType === 'x' || gapType === 'y') {
		if (gap) classes.push(`g-${gap}`);
		if (gapMd) classes.push(`g-md-${gapMd}`);
	}
	if (gapType === 'x' || gapType === 'general') {
		if (gapX) classes.push(`gx-${gapX}`);
		if (gapXMd) classes.push(`gx-md-${gapXMd}`);
	}
	if (gapType === 'y' || gapType === 'general') {
		if (gapY) classes.push(`gy-${gapY}`);
		if (gapYMd) classes.push(`gy-md-${gapYMd}`);
	}
	return classes.filter(Boolean);
};

export default function Edit({ attributes, setAttributes, clientId }) {
	const { gridType = 'classic', gridColumns = '3', gridRowCols, gridGapX, gridGapY, termsPerPage = -1, orderBy = 'meta_value_num', order = 'ASC', useAltTitle = true, showAllPracticeLink = true, blockClass = '' } = attributes;
	const [terms, setTerms] = useState([]);
	const [isLoading, setIsLoading] = useState(false);
	const blockProps = useBlockProps({ className: `horizons-practice-categories-grid-block ${blockClass}`, 'data-block': clientId });
	
	// Получаем URL сайта для построения ссылки на архив practices
	const siteUrl = useSelect((select) => {
		const siteInfo = select('core/site')?.getSiteInfo?.();
		return siteInfo?.url || '';
	}, []);
	useEffect(() => {
		const fetchTerms = async () => {
			setIsLoading(true);
			try {
				// REST API для таксономий не поддерживает параметры orderby и order
				// Используем базовый запрос без параметров сортировки, сортировку сделаем на клиенте
				const apiPath = `/wp/v2/practice_category?per_page=${termsPerPage === -1 ? 100 : termsPerPage}&_embed`;
				
				const fetchedTerms = await apiFetch({ path: apiPath });
				
				if (!Array.isArray(fetchedTerms)) {
					setTerms([]);
					setIsLoading(false);
					return;
				}
				
				// Преобразуем термины, сохраняя count из исходных данных
				let termsData = fetchedTerms.map((term) => {
					const displayTitle = term.name;
					return {
						id: term.id,
						name: term.name,
						displayTitle: displayTitle,
						slug: term.slug,
						link: term.link || `#${term.slug}`,
						color: '',
						colorClass: '',
						count: term.count || 0, // Сохраняем count для сортировки
						order: 0, // Будет заполнено при сортировке по мета-полю
					};
				});
				
				// Сортируем термины на клиенте в зависимости от выбранного типа сортировки
				if (orderBy === 'meta_value_num' || orderBy === 'meta_value') {
					// Для сортировки по мета-полю загружаем мета-данные для каждого термина
					const termsWithMeta = await Promise.all(
						termsData.map(async (term) => {
							try {
								const meta = await apiFetch({
									path: `/wp/v2/practice_category/${term.id}?_embed`,
								});
								const orderValue = meta.meta?.practice_category_order || '0';
								return {
									...term,
									order: parseInt(orderValue) || 0,
								};
							} catch (e) {
								return { ...term, order: 0 };
							}
						})
					);
					
					// Сортируем по order
					termsData = termsWithMeta.sort((a, b) => {
						if (order === 'ASC') {
							return a.order - b.order;
						} else {
							return b.order - a.order;
						}
					});
				} else if (orderBy === 'name' || orderBy === 'slug') {
					// Сортировка по имени или слагу
					termsData.sort((a, b) => {
						const aVal = a[orderBy].toLowerCase();
						const bVal = b[orderBy].toLowerCase();
						if (order === 'ASC') {
							return aVal.localeCompare(bVal);
						} else {
							return bVal.localeCompare(aVal);
						}
					});
				} else if (orderBy === 'count') {
					// Сортировка по количеству постов
					termsData.sort((a, b) => {
						if (order === 'ASC') {
							return a.count - b.count;
						} else {
							return b.count - a.count;
						}
					});
				}
				
				setTerms(termsData);
			} catch (error) {
				console.error('Practice Categories Grid: Error fetching terms:', error);
				setTerms([]);
			} finally {
				setIsLoading(false);
			}
		};
		fetchTerms();
	}, [termsPerPage, orderBy, order, useAltTitle]);
	const getContainerClasses = () => {
		const currentGridType = gridType || 'classic';
		if (currentGridType === 'columns-grid') {
			const rowColsClasses = getRowColsClasses(attributes, 'grid', gridColumns);
			const gapClasses = getGapClasses(attributes, 'grid');
			let gapClassesStr = gapClasses.join(' ');
			if (!gapClassesStr && (gridGapX || gridGapY)) {
				const oldGapClasses = [];
				if (gridGapY) oldGapClasses.push(`gy-${gridGapY}`);
				if (gridGapX) oldGapClasses.push(`gx-${gridGapX}`);
				gapClassesStr = oldGapClasses.join(' ');
			}
			return `row ${gapClassesStr} ${rowColsClasses.join(' ')}`;
		} else {
			const gapClasses = getGapClasses(attributes, 'grid');
			let gapClassesStr = gapClasses.join(' ');
			if (!gapClassesStr && (gridGapX || gridGapY)) {
				const oldGapClasses = [];
				if (gridGapY) oldGapClasses.push(`gy-${gridGapY}`);
				if (gridGapX) oldGapClasses.push(`gx-${gridGapX}`);
				gapClassesStr = oldGapClasses.join(' ');
			}
			return `row ${gapClassesStr}`.trim();
		}
	};
	
	const getColClasses = () => {
		if (gridType !== 'classic') {
			return '';
		}
		const colClasses = [];
		const {
			gridColumns: colsDefault,
			gridColumnsXs: colsXs,
			gridColumnsSm: colsSm,
			gridColumnsMd: colsMd,
			gridColumnsLg: colsLg,
			gridColumnsXl: colsXl,
			gridColumnsXxl: colsXxl,
		} = attributes;
		if (colsDefault) colClasses.push(`col-${12 / parseInt(colsDefault)}`);
		if (colsXs) colClasses.push(`col-${12 / parseInt(colsXs)}`);
		if (colsSm) colClasses.push(`col-sm-${12 / parseInt(colsSm)}`);
		if (colsMd) colClasses.push(`col-md-${12 / parseInt(colsMd)}`);
		if (colsLg) colClasses.push(`col-lg-${12 / parseInt(colsLg)}`);
		if (colsXl) colClasses.push(`col-xl-${12 / parseInt(colsXl)}`);
		if (colsXxl) colClasses.push(`col-xxl-${12 / parseInt(colsXxl)}`);
		return colClasses.join(' ');
	};
	return (
		<>
			<PracticeCategoriesGridSidebar attributes={attributes} setAttributes={setAttributes} />
			<div {...blockProps}>
				{isLoading ? (
					<div className="horizons-practice-categories-grid-placeholder" style={{ padding: '20px', textAlign: 'center' }}>{__('Loading categories...', 'horizons')}</div>
				) : terms.length === 0 ? (
					<div className="horizons-practice-categories-grid-placeholder" style={{ padding: '20px', textAlign: 'center' }}>{__('No categories found. Please ensure practice_category taxonomy exists and has terms.', 'horizons')}</div>
				) : (
					<div className={getContainerClasses()}>
						{terms.map((term) => (
							<div key={term.id} className={gridType === 'classic' ? getColClasses() : (gridType === 'columns-grid' ? 'col' : '')}>
								<div className="card h-100 practice-card">
									<div className={`brand-square-xs ${term.colorClass} opacity-0 position-absolute top-0 start-0`}></div>
									<div className="card-body p-4 p-md-8 d-flex flex-column justify-content-between">
										<div className="pe-none mb-5">
											<div className={`practice-card-hover brand-square-md ${term.colorClass}`}></div>
										</div>
										<h3 className="h4">{term.displayTitle}</h3>
										<div className="icontext right label-s text-white opacity-0 position-absolute" style={{ transition: 'all .8s ease', top: '45px', left: '45px' }}>
											{__('More details', 'horizons')}
										</div>
									</div>
								</div>
							</div>
						))}
						{showAllPracticeLink && (
							<div className={gridType === 'classic' ? getColClasses() : (gridType === 'columns-grid' ? 'col' : '')}>
								<div className="card practice-card bg-dusty-navy h-100">
									<div className="card-body p-4 p-md-8 align-content-center text-center"><span className="hover-4 link-body label-s text-sub-white">{__('All Practice', 'horizons')}</span></div>
								</div>
							</div>
						)}
					</div>
				)}
			</div>
		</>
	);
}
