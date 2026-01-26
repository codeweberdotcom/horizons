const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const path = require('path');

module.exports = {
	...defaultConfig,
	output: {
		...defaultConfig.output,
		clean: {
			keep: (filename) => {
				// НИКОГДА не удаляем исходные файлы блоков
				const normalizedPath = filename.replace(/\\/g, '/');
				
				// Сохраняем ВСЕ исходные файлы блоков practice-categories-grid
				if (normalizedPath.includes('blocks/practice-categories-grid/')) {
					const basename = path.basename(filename);
					if (/^(block\.json|render\.php|edit\.js|save\.js|sidebar\.js|editor\.scss|style\.scss|index\.src\.js)$/.test(basename)) {
						return true;
					}
					if (basename === 'controls' || normalizedPath.includes('blocks/practice-categories-grid/controls/')) {
						return true;
					}
					if (/^(index\.js|index\.asset\.php|index\.css|style-index\.css)$/.test(basename)) {
						return true;
					}
				}
				
				// Сохраняем ВСЕ исходные файлы блоков partners-grid
				if (normalizedPath.includes('blocks/partners-grid/')) {
					const basename = path.basename(filename);
					// Защищаем ВСЕ исходные файлы
					if (/^(block\.json|render\.php|edit\.js|save\.js|sidebar\.js|editor\.scss|style\.scss|index\.src\.js)$/.test(basename)) {
						return true;
					}
					// Защищаем папку controls и все файлы внутри
					if (basename === 'controls' || normalizedPath.includes('blocks/partners-grid/controls/')) {
						return true;
					}
					// Защищаем скомпилированные файлы
					if (/^(index\.js|index\.asset\.php|index\.css|style-index\.css|index\.src\.css|style-index\.src\.css)$/.test(basename)) {
						return true;
					}
					// По умолчанию для partners-grid сохраняем все
					return true;
				}
				
				// Удаляем только файлы из blocks/post-grid (это артефакты)
				if (normalizedPath.includes('blocks/post-grid')) {
					return false;
				}
				
				// По умолчанию сохраняем файлы
				return true;
			},
		},
	},
};

