# Partners Map Block

Gutenberg-блок для отображения партнёров на Яндекс Карте v3, сгруппированных по регионам и странам.

**Namespace:** `horizons-blocks/partners-map`  
**Версия блока:** 1.1.0  
**Расположение:** `blocks/partners-map/`

---

## Файлы

| Файл | Назначение |
|------|-----------|
| `block.json` | Декларация блока: атрибуты, supports, зависимости |
| `index.js` | Минифицированный редакторный скрипт (собирается из `index.src.js`) |
| `index.src.js` | Исходник editor-скрипта (JSX-стиль, но без npm build — редактируется напрямую в `index.js`) |
| `index.asset.php` | Хеш версии для cache-busting WordPress; при правке `index.js` менять вручную |
| `render.php` | Серверный рендер блока на фронте |
| `partners-map.js` | Основной фронтенд-скрипт (Yandex Maps v3, подключается через `render.php`) |
| `style.css` | CSS блока (подключается через WordPress как `file:./style.css`) |
| `edit.js` | Не используется как отдельный файл — код вшит в `index.js` |
| `save.js` | `return null` (динамический блок) |

> **Важно:** `index.js` — минифицированный файл, редактируется напрямую. JSX-источник в `index.src.js` не собирается автоматически. При изменениях в `index.js` также обновлять хеш в `index.asset.php` для сброса кэша WordPress.

---

## Зависимости

- `yandex-maps-api-v3` — регистрируется в теме через `wp_register_script`
- `horizons-partners-map` — `partners-map.js`, версия `1.3.1`, зависит от `yandex-maps-api-v3`

Оба скрипта подключаются из `render.php` через `wp_enqueue_script`.

---

## Атрибуты (`block.json`)

### Карта

| Атрибут | Тип | По умолчанию | Описание |
|---------|-----|-------------|---------|
| `dataSource` | string | `"both"` | Источник данных: `"both"` / `"regions"` / `"countries"` |
| `height` | number | `600` | Высота карты в пикселях (min 300) |
| `zoom` | number | `4` | Начальный зум (1–19) |
| `centerLat` | number | `55.76` | Начальный центр: широта |
| `centerLng` | number | `37.64` | Начальный центр: долгота |
| `mapType` | string | `"normal"` | Тип карты: `"normal"` / `"satellite"` / `"hybrid"` |
| `scrollZoom` | boolean | `false` | Зум колёсиком мыши |
| `autoFitBounds` | boolean | `true` | Авто-подгонка всех маркеров при инициализации |
| `styleJson` | string | `""` | Кастомный JSON-стиль карты (customization Яндекс) |
| `zoomMin` | number | `2` | Минимальный зум |
| `zoomMax` | number | `19` | Максимальный зум |
| `zoomControl` | boolean | `true` | Показывать кастомные кнопки +/− |

### Маркеры

| Атрибут | Тип | По умолчанию | Описание |
|---------|-----|-------------|---------|
| `markerColor` | string | `"#C8A96E"` | Цвет фона маркера |
| `markerSize` | number | `40` | Размер маркера в пикселях |
| `markerShape` | string | `"circle"` | Форма: `"circle"` / `"square"` |
| `markerShowCount` | boolean | `true` | Показывать число партнёров на маркере |
| `markerShowLabel` | boolean | `false` | Показывать подпись (название региона/страны) рядом с маркером |
| `markerLabelSize` | number | `11` | Размер шрифта подписи (px) |
| `markerLabelColor` | string | `"#1a1a1a"` | Цвет подписи |
| `labelLatThreshold` | number | `5` | Порог близости по широте (°) для сдвига перекрывающихся подписей |
| `labelDistThreshold` | number | `120` | Порог расстояния в пикселях для сдвига перекрывающихся подписей |
| `clustererEnabled` | boolean | `false` | Зарезервировано (кластеризация не реализована) |

### Сайдбар

| Атрибут | Тип | По умолчанию | Описание |
|---------|-----|-------------|---------|
| `sidebarEnabled` | boolean | `true` | Показывать сайдбар |
| `sidebarPosition` | string | `"left"` | Позиция: `"left"` / `"right"` |
| `sidebarTitle` | string | `""` | Заголовок сайдбара (по умолчанию «Countries») |
| `sidebarColsSm` | number | `1` | Колонки в сайдбаре на малом экране |
| `sidebarColsMd` | number | `1` | Колонки на экране ≥768px |
| `sidebarColsLg` | number | `1` | Колонки на экране ≥1024px |

---

## Архитектура данных (`render.php`)

### Таксономии

Блок работает с двумя таксономиями CPT `partners`:

- `partner_region` — регионы. Мета-поля: `partner_lat`, `partner_lng`
- `partner_country` — страны. Мета-поля: `partner_lat`, `partner_lng`

### Логика сборки маркеров

1. Собираются термины `partner_region` и `partner_country` с непустыми координатами
2. Перебираются все опубликованные `partners`; каждый партнёр назначается:
   - в регион, если у него есть `partner_region` с координатами (`assigned = true`)
   - в страну (fallback), если регион не найден
3. Строится маппинг `region_id → country_id` (`$region_to_country`)
4. Формируется `$markers_json` — массив объектов для JS:
   ```json
   { "termId": 5, "termType": "region", "lat": 55.76, "lng": 37.64,
     "title": "Москва", "count": 3, "parentId": 12 }
   ```
5. Строится `$sidebar_items` — дерево страна → регионы для рендера фильтра

### Конфиг карты

Все настройки передаются в JS через `data-map-config` атрибут обёртки в виде JSON. В `partners-map.js` читается как `JSON.parse(wrapper.getAttribute('data-map-config'))`.

---

## Frontend архитектура (`partners-map.js`)

### Инициализация

```
document.querySelectorAll('.horizons-partners-map')
  → для каждого враппера читает data-map-config
  → ждёт ymaps3.ready
  → вызывает initMap(wrapper, canvas, cfg)
```

### Структура маркера

```html
<div class="hpm-marker-wrap" style="transform:translate(0,-50%)">
  <div class="hpm-dot">3</div>          <!-- число партнёров -->
  <span class="hpm-marker-label">...</span>  <!-- если markerShowLabel -->
</div>
```

`transform: translate(0, -50%)` центрирует маркер по вертикали на точке координат.

При зуме маркеры масштабируются через `applyScale()` — размер растёт по формуле `1.18^(currentZoom - baseZoom)`.

### Отслеживание текущего зума

`map.location` в YMaps v3 возвращает `null` — использовать нельзя. Вместо этого:
```js
var currentZoom = zoom;  // начальное значение
// в onUpdate:
currentZoom = update.location.zoom;
```

Кнопки +/− используют `currentZoom` для вычисления нового значения с учётом `zoomMin`/`zoomMax`.

### Ограничение зума

Двойное ограничение:
1. `zoomRange: { min, max }` в конструкторе `YMap` — работает для scroll/pinch нативно
2. `onUpdate` clamp — дополнительная защита для кастомных кнопок

### Сдвиг перекрывающихся подписей (`applyLabelOffsets`)

Вызывается при инициализации (через 600ms) и после каждого зума (debounce 300ms).

Два условия для сдвига пары маркеров `i` и `j`:
- `|lat[i] - lat[j]| < labelLatThreshold` (близость по широте)
- `pixelDistance(i, j) < labelDistThreshold` (близость на экране)

При срабатывании оба **wrap**'а (не только label) сдвигаются вертикально:
- верхний маркер: `translate(0, calc(-50% - Npx))`
- нижний маркер: `translate(0, calc(-50% + Npx))`

где `N = round(markerSize / 2) + 4`.

Важно: сдвигается именно `wrap`, а не `label` — чтобы точка и подпись двигались вместе как единица.

### Кэш данных партнёров

```js
var partnersCache = {};  // { key: { data, ts } }
var CACHE_TTL = 15 * 60 * 1000;  // 15 минут
```

Ключ кэша: `termType + '_' + termId`.

После инициализации карты все маркеры предзагружаются в фоне через `preloadAllPartners()`, чтобы попапы открывались мгновенно.

### Попап

При клике на маркер открывается `YMapMarker` с DOM-контейнером.
- Если данные есть в кэше — рендер мгновенно
- Иначе — показывается pill «Loading…», данные грузятся через REST API `/wp-json/wp/v2/partners`
- Показывается до 8 партнёров, если больше — ссылка «All partners (N)»
- `autoPan`: если попап выходит за правый/нижний край канваса — карта сдвигается
- Флип: если попап выходит за правый край — применяется `translateX(-100%)` для открытия влево

Закрытие: клик по карте вне маркера.

### Сайдбар

**Фильтрация по клику:**
- `data-filter="all"` — показывает все маркеры, подгоняет bounds, очищает поисковую строку
- `data-filter="term"` + `data-term-id` — центрирует карту на маркере, открывает попап
- Если у страны нет собственного маркера (только регионы) — подгоняет bounds по регионам

**Поиск:**
- Поле `input[type=search]` фильтрует country-group по тексту кнопки
- Standalone регионы (без страны) тоже фильтруются
- При клике «All partners» поиск очищается и все элементы восстанавливаются

**Сворачивание (мобильный/десктоп):**
- Класс `is-sidebar-collapsed` скрывает сайдбар через `translateX(-100%)`
- Кнопка `.horizons-partners-map__toggle-btn` видна только в свёрнутом состоянии
- На мобильных (<640px) сайдбар становится горизонтальной полосой сверху

---

## CSS-классы

| Класс | Элемент |
|-------|---------|
| `.horizons-partners-map-block` | Корневой wrapper блока (WordPress) |
| `.horizons-partners-map` | Основная обёртка карты |
| `.horizons-partners-map--no-sidebar` | Модификатор: без сайдбара |
| `.horizons-partners-map--sidebar-right` | Сайдбар справа |
| `.is-sidebar-collapsed` | Сайдбар свёрнут |
| `.horizons-partners-map__canvas` | Холст карты (фон `#333333` при загрузке тайлов) |
| `.horizons-partners-map__sidebar` | Боковая панель |
| `.horizons-partners-map__toggle-btn` | Кнопка открытия сайдбара |
| `.horizons-partners-map__sidebar-close-btn` | Кнопка закрытия сайдбара |
| `.horizons-partners-map__sidebar-search` | Обёртка поля поиска |
| `.horizons-partners-map__search-input` | Поле поиска |
| `.horizons-partners-map__filter-btn` | Кнопка фильтра (страна/регион/all) |
| `.horizons-partners-map__filter-btn.is-active` | Активный фильтр |
| `.horizons-partners-map__filter-region` | Кнопка региона |
| `.horizons-partners-map__filter-country` | Кнопка страны |
| `.horizons-partners-map__country-group` | Группа: страна + её регионы |
| `.horizons-partners-map__regions` | Список регионов внутри группы |
| `.hpm-marker-wrap` | Обёртка маркера (dot + label) |
| `.hpm-dot` | Цветной круг/квадрат маркера |
| `.hpm-marker-label` | Подпись маркера |
| `.hpm-popup__header` | Шапка попапа |
| `.hpm-popup__body` | Тело попапа |

---

## REST API

Данные партнёров для попапов загружаются через стандартный WordPress REST API:

```
GET /wp-json/wp/v2/partners
  ?per_page=100
  &_embed=wp:featuredmedia
  &_fields=id,title,link,meta,_embedded,featured_media,_links
  &partner_region={termId}   // или partner_country={termId}
```

Дополнительный endpoint для редактора (Inspector):
```
GET /wp-json/horizons/v1/partners-map-data?dataSource={both|regions|countries}
```

---

## Кэш тайлов

Яндекс отдаёт тайлы с `Cache-Control: max-age=900` (15 минут) и `Vary: Origin`.
- В течение 15 минут тайлы кэшируются браузером
- После 15 минут браузер делает условный запрос с `If-None-Match`, Яндекс отвечает `304`
- TTL задаётся со стороны Яндекса, изменить невозможно
- Redis и Service Worker не помогут: тайлы загружаются через WebGL/Worker, не через сервер

---

## Gotchas

- **`map.location` = null в YMaps v3** — нельзя использовать для получения текущего зума. Зум отслеживается через переменную `currentZoom`, обновляемую в `onUpdate`.
- **Trailing comma в `index.js`** — после удаления ToggleControl в Inspector остаётся `,)` перед следующим PanelBody. Это валидный синтаксис ES2017+, ошибкой не является.
- **JSON.parse в `index.js`** — строка с атрибутами блока вшита как `JSON.parse('...')`. При правке атрибутов следить за балансом фигурных скобок; лишняя `}` ломает блок с ошибкой «JSON.parse error at column 1900».
- **Версия `index.asset.php`** — WordPress кэширует редакторный скрипт по хешу. После ручной правки `index.js` менять хеш в `index.asset.php`, иначе редактор будет грузить старую версию.
- **`applyLabelOffsets` работает только когда `showLabel = true`** — при отключённых подписях функция выходит сразу и transform на `wrap` не сбрасывается (но и не нужен).
- **Маркеры масштабируются относительно `baseZoom`** — `baseZoom` фиксируется при инициализации и не меняется. Это значит, что при `autoFitBounds` реальный начальный зум после подгонки может отличаться от `cfg.zoom`, но `baseZoom` останется `cfg.zoom`.
