$(document).ready(function () {
  if (!$.isFunction($.fn.select2)) {
    return;
  }

  var $target = $('.select2.select_ajax');
  $target.each(function () {
    changeSelectAjax($(this));
  });

  $target.on("select2:select select2:unselecting", function () {
    changeSelectAjax($(this));
  });

  function changeSelectAjax($target) {
    var url = $target.data('url');
    if (!url) {
      console.warn('url is not defined');
      return;
    }

    $target.select2({
      ajax: {
        url: url,
        data: function (params) {
          var query = {
            search: params.term,
            page: params.page || 1
          }
          return query;
        },
        processResults: function (data, params) {
          return {
            'results': data.items,
            'pagination': {
              more: (data.pagination.page * data.pagination.items_per_page) < data.pagination.total
            }
          };
        }
      }
    });
  }
});
