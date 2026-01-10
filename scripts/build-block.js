const { execSync } = require('child_process');
const fs = require('fs');
const path = require('path');

const blockDir = path.join(__dirname, '../blocks/practice-categories-grid');
const tempDir = path.join(__dirname, '../.temp-build-cache/practice-categories-grid');

// Файлы и папки для сохранения
const filesToKeep = [
	'block.json',
	'render.php',
	'edit.js',
	'save.js',
	'sidebar.js',
	'editor.scss',
	'style.scss',
	'controls',
	'index.src.js', // Исходный файл для сборки
];

console.log('📦 Сохраняю исходные файлы...');

// Создаем временную директорию для исходных файлов
if (!fs.existsSync(tempDir)) {
	fs.mkdirSync(tempDir, { recursive: true });
}

// Восстанавливаем исходный index.js из index.src.js перед сборкой (если его нет)
const srcIndexPath = path.join(blockDir, 'index.src.js');
const indexPath = path.join(blockDir, 'index.js');
if (fs.existsSync(srcIndexPath) && !fs.existsSync(indexPath)) {
	fs.copyFileSync(srcIndexPath, indexPath);
	console.log('✓ Восстановлен исходный index.js из index.src.js');
}

// Сохраняем исходные файлы
filesToKeep.forEach(file => {
	const sourcePath = path.join(blockDir, file);
	const destPath = path.join(tempDir, file);
	
	if (fs.existsSync(sourcePath)) {
		const stat = fs.statSync(sourcePath);
		if (stat.isDirectory()) {
			// Копируем директорию
			copyDirectory(sourcePath, destPath);
		} else {
			// Копируем файл
			fs.copyFileSync(sourcePath, destPath);
		}
		console.log(`✓ Сохранен: ${file}`);
	}
});

function copyDirectory(src, dest) {
	if (!fs.existsSync(dest)) {
		fs.mkdirSync(dest, { recursive: true });
	}
	
	const entries = fs.readdirSync(src, { withFileTypes: true });
	
	for (let entry of entries) {
		const srcPath = path.join(src, entry.name);
		const destPath = path.join(dest, entry.name);
		
		if (entry.isDirectory()) {
			copyDirectory(srcPath, destPath);
		} else {
			fs.copyFileSync(srcPath, destPath);
		}
	}
}

console.log('🔨 Запускаю сборку...');

try {
	// Запускаем сборку
	execSync('npm run build:block-only', { 
		cwd: path.join(__dirname, '..'),
		stdio: 'inherit'
	});
	
	console.log('✅ Сборка завершена');
} catch (error) {
	console.error('❌ Ошибка при сборке:', error.message);
	process.exit(1);
}

console.log('📥 Восстанавливаю исходные файлы...');

// Восстанавливаем исходные файлы
filesToKeep.forEach(file => {
	const sourcePath = path.join(tempDir, file);
	const destPath = path.join(blockDir, file);
	
	if (fs.existsSync(sourcePath)) {
		const stat = fs.statSync(sourcePath);
		if (stat.isDirectory()) {
			// Удаляем старую директорию и копируем новую
			if (fs.existsSync(destPath)) {
				fs.rmSync(destPath, { recursive: true, force: true });
			}
			copyDirectory(sourcePath, destPath);
		} else {
			// Копируем файл
			fs.copyFileSync(sourcePath, destPath);
		}
		console.log(`✓ Восстановлен: ${file}`);
	}
});

// Удаляем папку blocks/post-grid если она появилась из-за копирования файлов плагина
const unwantedBlocksDir = path.join(blockDir, 'blocks');
if (fs.existsSync(unwantedBlocksDir)) {
	fs.rmSync(unwantedBlocksDir, { recursive: true, force: true });
	console.log('🗑️  Удалена папка blocks/post-grid');
}

// НЕ удаляем временную директорию - оставляем для отладки
// if (fs.existsSync(tempDir)) {
// 	fs.rmSync(tempDir, { recursive: true, force: true });
// }

console.log('✨ Готово! Все исходные файлы сохранены и восстановлены.');

