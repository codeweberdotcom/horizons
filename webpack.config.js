const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const path = require('path');

module.exports = {
	...defaultConfig,
	output: {
		...defaultConfig.output,
		clean: {
			keep: (filename) => {
				// Сохраняем исходные файлы блока
				if (/^(block\.json|render\.php|edit\.js|save\.js|sidebar\.js|editor\.scss|style\.scss|controls|index\.js)$/.test(path.basename(filename))) {
					return true;
				}
				// Удаляем папку blocks/post-grid если она появилась
				if (filename.includes('blocks/post-grid')) {
					return false;
				}
				return false;
			},
		},
	},
};

