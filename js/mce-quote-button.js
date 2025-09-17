(function () {
  tinymce.PluginManager.add("horizons_mce", function (editor, url) {
    // Кнопка для вставки блока цитаты
    editor.addButton("horizons_quote", {
      text: "Цитата",
      icon: false,
      onclick: function () {
        editor.windowManager.open({
          title: "Вставка блока цитаты",
          body: [
            {
              type: "textbox",
              name: "quote_text",
              label: "Текст цитаты",
              value:
                "Если чего-то сильно желаешь, имей смелость поставить всё на кон ради достижения цели. Что бы ни случилось, будь всегда добрым!",
            },
            {
              type: "textbox",
              name: "author",
              label: "Автор цитаты",
              value: "Роберто Джилардино",
            },
          ],
          onsubmit: function (e) {
            var html =
              '<div class="card mb-5">' +
              '<div class="card-body bg-neutral-200">' +
              '<figure class="mb-0">' +
              '<blockquote class="icon body-l-r">' +
              e.data.quote_text +
              "</blockquote>" +
              '<figcaption class="text-square-before label-u text-neutral-500 mb-0">' +
              e.data.author +
              "</figcaption>" +
              "</figure>" +
              "</div>" +
              "</div>";

            editor.insertContent(html);
          },
        });
      },
    });
  });
})();
