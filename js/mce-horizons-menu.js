(function () {
  tinymce.PluginManager.add("horizons_mce", function (editor, url) {
    // Создаем выпадающее меню Horizons Elements
    editor.addButton("horizons_elements_menu", {
      type: "menubutton",
      text: "Horizons Elements",
      icon: false,
      menu: [
        {
          text: "Цитаты",
          menu: [
            {
              text: "Цитата с автором",
              onclick: function () {
                editor.windowManager.open({
                  title: "Цитата с автором",
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
            },
            {
              text: "Простая цитата",
              onclick: function () {
                var html =
                  '<blockquote class="simple-quote" style="border-left: 3px solid #0073aa; padding: 10px 20px; margin: 15px 0; font-style: italic;">' +
                  "Ваш текст цитаты здесь" +
                  "</blockquote>";
                editor.insertContent(html);
              },
            },
          ],
        },
        {
          text: "Уведомления",
          menu: [
            {
              text: "Информационное уведомление",
              onclick: function () {
                var html =
                  '<div class="alert alert-info" style="background: #e7f3ff; border: 1px solid #b8daff; padding: 15px; border-radius: 5px; margin: 15px 0;">' +
                  "<strong>Информация:</strong> Ваше информационное сообщение" +
                  "</div>";
                editor.insertContent(html);
              },
            },
            {
              text: "Предупреждение",
              onclick: function () {
                var html =
                  '<div class="alert alert-warning" style="background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 5px; margin: 15px 0;">' +
                  "<strong>Внимание:</strong> Ваше предупреждение" +
                  "</div>";
                editor.insertContent(html);
              },
            },
            {
              text: "Успех",
              onclick: function () {
                var html =
                  '<div class="alert alert-success" style="background: #d4edda; border: 1px solid #c3e6cb; padding: 15px; border-radius: 5px; margin: 15px 0;">' +
                  "<strong>Успех:</strong> Ваше сообщение об успехе" +
                  "</div>";
                editor.insertContent(html);
              },
            },
          ],
        },
        {
          text: "Кнопки",
          onclick: function () {
            editor.windowManager.open({
              title: "Вставка кнопки",
              body: [
                {
                  type: "textbox",
                  name: "button_text",
                  label: "Текст кнопки",
                  value: "Нажми меня",
                },
                {
                  type: "textbox",
                  name: "button_url",
                  label: "URL ссылки",
                  value: "https://",
                },
                {
                  type: "listbox",
                  name: "button_style",
                  label: "Стиль кнопки",
                  values: [
                    { text: "Основная", value: "primary" },
                    { text: "Вторичная", value: "secondary" },
                    { text: "Успех", value: "success" },
                  ],
                },
              ],
              onsubmit: function (e) {
                var styleClass = "btn-" + e.data.button_style;
                var html =
                  '<a href="' +
                  e.data.button_url +
                  '" class="btn ' +
                  styleClass +
                  '" style="display: inline-block; padding: 10px 20px; margin: 10px 0; text-decoration: none;">' +
                  e.data.button_text +
                  "</a>";

                editor.insertContent(html);
              },
            });
          },
        },
        {
          text: "Разделитель",
          onclick: function () {
            var html =
              '<hr class="custom-divider" style="border: 0; height: 2px; background: linear-gradient(90deg, transparent, #0073aa, transparent); margin: 30px 0;">';
            editor.insertContent(html);
          },
        },
        {
          text: "Спойлер",
          onclick: function () {
            editor.windowManager.open({
              title: "Вставка спойлера",
              body: [
                {
                  type: "textbox",
                  name: "spoiler_title",
                  label: "Заголовок спойлера",
                  value: "Нажмите чтобы раскрыть",
                },
                {
                  type: "textbox",
                  name: "spoiler_content",
                  label: "Содержание спойлера",
                  multiline: true,
                  value: "Скрытое содержание здесь...",
                },
              ],
              onsubmit: function (e) {
                var html =
                  '<div class="spoiler" style="margin: 15px 0;">' +
                  '<div class="spoiler-title" style="background: #f8f9fa; padding: 10px 15px; border: 1px solid #dee2e6; cursor: pointer; border-radius: 5px;">' +
                  "<strong>" +
                  e.data.spoiler_title +
                  "</strong>" +
                  "</div>" +
                  '<div class="spoiler-content" style="display: none; padding: 15px; border: 1px solid #dee2e6; border-top: none; border-radius: 0 0 5px 5px;">' +
                  e.data.spoiler_content +
                  "</div>" +
                  "</div>";

                editor.insertContent(html);
              },
            });
          },
        },
      ],
    });
  });
})();
