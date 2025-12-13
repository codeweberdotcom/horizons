<?php
/**
 * Отключение виджетов из родительской темы
 * 
 * Простое отключение через remove_action для именованных функций
 * Используем хук after_setup_theme с приоритетом, чтобы гарантировать загрузку хуков родительской темы
 * 
 * Доступные виджеты для отключения:
 * - codeweber_sidebar_widget_legal (Legal)
 * - codeweber_sidebar_widget_vacancies (Vacancies)
 * - codeweber_sidebar_widget_faq (FAQ)
 */
function horizons_remove_parent_widgets() {
    // Отключить виджет Vacancies из родительской темы
    remove_action('codeweber_after_widget', 'codeweber_sidebar_widget_vacancies');
    
    // Примеры отключения других виджетов (раскомментируйте при необходимости):
    // remove_action('codeweber_after_widget', 'codeweber_sidebar_widget_legal');
    // remove_action('codeweber_after_sidebar', 'codeweber_sidebar_widget_faq');
}
add_action('after_setup_theme', 'horizons_remove_parent_widgets', 20);

