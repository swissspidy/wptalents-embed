/**
 * Meta box functionality script.
 */

(function ($) {

  var wptalents_embed_talents = $('#wptalents_embed_talents');

  /**
   * Capitalize the first letter of a talent's type.
   * @param string
   * @returns {string}
   */
  function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
  }

  /**
   * This makes our talents list sortable.
   */
  $(wptalents_embed_talents).sortable().disableSelection();

  $('.wptalents_embed_remove_talent').on('click', function () {
    $(this).parent('li').remove();
  });

  // Simple Autocomplete Cache
  var cache = {};

  $("#wptalents_embed_search").autocomplete({
    minLength: 3,
    source   : function (request, response) {
      var term = request.term;
      if (term in cache) {
        response(cache[term]);
        return;
      }

      var filter = {
        filter: {s: request.term}
      };

      $.getJSON("https://wptalents.com/api/talents/", filter, function (data, status, xhr) {
        cache[term] = data;
        response(data);
      });
    },
    create   : function () {
      $(this).data('ui-autocomplete')._renderItem = function (ul, item) {
        return $("<li>")
            .append("<a>" + item.title + "<br>" + capitalizeFirstLetter(item.type) + "</a>")
            .appendTo(ul);
      };
    },
    focus    : function () {
      // prevent value inserted on focus
      return false;
    },
    select   : function (event, ui) {
      if ($('#wptalents_embed_talents').find('li').data('wptalents-id') == ui.item.ID) {
        return;
      }

      var li = $("<li />").text(ui.item.title).data('wptalents-id', ui.item.ID);

      li.append($("<input />").attr({type: "hidden", name: "wptalents_embed_talents[id][]"}).val(ui.item.ID));
      li.append($("<input />").attr({type: "hidden", name: "wptalents_embed_talents[name][]"}).val(ui.item.title));
      li.append($("<button />").attr({
        type : "button",
        title: 'Remove talent',
        class: 'wptalents_embed_remove_talent button-secondary'
      }).text('X'));
      $(wptalents_embed_talents).append(li);

      $('.wptalents_embed_remove_talent').on('click', function () {
        $(this).parent('li').remove();
      });

    }
  });

})(jQuery);