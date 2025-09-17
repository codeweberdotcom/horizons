jQuery(document).ready(function ($) {
  // Работа спойлеров
  $(document).on("click", ".spoiler-title", function () {
    $(this).next(".spoiler-content").slideToggle();
  });
});
