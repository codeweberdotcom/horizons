document.addEventListener("DOMContentLoaded", function () {
  console.log("Awards filter script loaded");

  // Обработчики для всех меню фильтрации
  setupFilterMenu("awards-category-nav", "category");
  setupFilterMenu("awards-year-nav", "year");
  setupFilterMenu("awards-partners-nav", "partner");

  function setupFilterMenu(menuId, filterType) {
    const menu = document.getElementById(menuId);
    if (!menu) return;

    const links = menu.querySelectorAll("a");

    links.forEach((link) => {
      link.addEventListener("click", function (e) {
        e.preventDefault();

        // Удаляем активный класс у всех ссылок в ЭТОМ меню
        links.forEach((l) => l.classList.remove("active"));
        // Добавляем активный класс к текущей ссылке
        this.classList.add("active");

        // Выполняем фильтрацию
        filterAwards(filterType, this.href);
      });
    });
  }

  function filterAwards(filterType, filterUrl) {
    // Показываем индикатор загрузки
    showLoadingIndicator();

    const data = new FormData();
    data.append("action", "filter_awards");
    data.append("filter_type", filterType);
    data.append("filter_url", filterUrl);
    data.append("nonce", awardsAjax.nonce);

    fetch(awardsAjax.ajaxurl, {
      method: "POST",
      body: data,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          // Заменяем содержимое .isotope контейнера
          const isotopeContainer = document.querySelector(
            ".awards-grid .isotope"
          );
          if (isotopeContainer) {
            isotopeContainer.innerHTML = data.data.html;

            // Обновляем блок активных фильтров
            const activeFiltersBlock = document.querySelector(
              ".active-filters-block"
            );
            if (activeFiltersBlock && data.data.active_filters_html) {
              activeFiltersBlock.outerHTML = data.data.active_filters_html;
            } else if (!activeFiltersBlock && data.data.active_filters_html) {
              // Если блока еще нет, добавляем его перед первым виджетом
              const firstWidget = document.querySelector(".widget");
              if (firstWidget) {
                firstWidget.insertAdjacentHTML(
                  "beforebegin",
                  data.data.active_filters_html
                );
              }
            } else if (activeFiltersBlock && !data.data.active_filters_html) {
              // Если фильтров нет, удаляем блок
              activeFiltersBlock.remove();
            }

            // Обновляем активные классы в других меню
            updateOtherMenus(filterType);

            // Инициализируем компоненты темы
            initThemeComponents();
          }

          // Обновляем URL
          window.history.pushState({}, "", filterUrl);
        } else {
          console.error("AJAX Error:", data.data);
        }
      })
      .catch((error) => {
        console.error("Fetch Error:", error);
      })
      .finally(() => {
        hideLoadingIndicator();
      });
  }

  // Функция для сброса активных классов в других меню
  function updateOtherMenus(currentFilterType) {
    const menus = {
      category: "awards-category-nav",
      year: "awards-year-nav",
      partner: "awards-partners-nav",
    };

    // Сбрасываем активные классы во всех меню, кроме текущего
    Object.keys(menus).forEach((filterType) => {
      if (filterType !== currentFilterType) {
        const menu = document.getElementById(menus[filterType]);
        if (menu) {
          const links = menu.querySelectorAll("a");
          links.forEach((link) => link.classList.remove("active"));
        }
      }
    });
  }

  function initThemeComponents() {
    if (typeof theme !== "undefined") {
      if (typeof theme.isotope === "function") {
        theme.isotope();
        console.log("Isotope reinitialized");
      }
      if (typeof theme.imageHoverOverlay === "function") {
        theme.imageHoverOverlay();
        console.log("Image hover overlay reinitialized");
      }
    } else {
      console.warn("Theme object not found");
      initComponentsManually();
    }
  }

  function initComponentsManually() {
    const grid = document.querySelector(".isotope");
    if (grid && typeof Isotope !== "undefined") {
      new Isotope(grid, {
        itemSelector: ".item",
        layoutMode: "fitRows",
      });
      console.log("Isotope initialized manually");
    }
    initHoverEffects();
  }

  function initHoverEffects() {
    const cards = document.querySelectorAll(".hover-scale");
    cards.forEach((card) => {
      card.addEventListener("mouseenter", function () {
        this.style.transform = "scale(1.05)";
      });
      card.addEventListener("mouseleave", function () {
        this.style.transform = "scale(1)";
      });
    });
  }

  function showLoadingIndicator() {
    const gridContainer = document.getElementById("awards-grid");
    if (gridContainer) {
      gridContainer.style.opacity = "0.7";
      gridContainer.style.pointerEvents = "none";
    }
  }

  function hideLoadingIndicator() {
    const gridContainer = document.getElementById("awards-grid");
    if (gridContainer) {
      gridContainer.style.opacity = "1";
      gridContainer.style.pointerEvents = "auto";
    }
  }

  // Обработка кнопки "назад" в браузере
  window.addEventListener("popstate", function () {
    window.location.reload();
  });

  // Автоматически активируем текущий фильтр из URL
  function setActiveFiltersFromURL() {
    const currentUrl = window.location.href;
    const url = new URL(currentUrl);

    const menus = [
      "awards-category-nav",
      "awards-year-nav",
      "awards-partners-nav",
    ];

    // Сначала сбрасываем все активные классы
    menus.forEach((menuId) => {
      const menu = document.getElementById(menuId);
      if (!menu) return;
      const links = menu.querySelectorAll("a");
      links.forEach((link) => link.classList.remove("active"));
    });

    // Затем активируем соответствующие фильтры

    // Для категорий (из таксономии)
    if (window.location.pathname.includes("/award-category/")) {
      const pathParts = window.location.pathname.split("/");
      const categorySlug = pathParts[pathParts.length - 2];
      const categoryLink = document.querySelector(
        `#awards-category-nav a[href*="${categorySlug}"]`
      );
      if (categoryLink) {
        categoryLink.classList.add("active");
      }
    }
    // Для категорий из параметра
    else if (url.searchParams.get("category")) {
      const categorySlug = url.searchParams.get("category");
      const categoryLink = document.querySelector(
        `#awards-category-nav a[href*="category=${categorySlug}"]`
      );
      if (categoryLink) {
        categoryLink.classList.add("active");
      }
    }

    // Для годов
    if (url.searchParams.get("year")) {
      const year = url.searchParams.get("year");
      const yearLink = document.querySelector(
        `#awards-year-nav a[href*="year=${year}"]`
      );
      if (yearLink) {
        yearLink.classList.add("active");
      }
    }

    // Для партнеров
    if (url.searchParams.get("partner")) {
      const partner = url.searchParams.get("partner");
      const partnerLink = document.querySelector(
        `#awards-partners-nav a[href*="partner=${partner}"]`
      );
      if (partnerLink) {
        partnerLink.classList.add("active");
      }
    }
  }

  setActiveFiltersFromURL();
});
