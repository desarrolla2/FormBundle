$(document).ready(function () {
  var $target = $('.select2.select_ajax');
  $target.each(function () {
    changeSelectAjax($(this));
  });

  $target.change(function () {
    changeSelectAjax($(this));
  });

  function changeSelectAjax(target) {
    if (target.data('url') == '') {
      throw 'url is not defined'
    }
    if (!$.isFunction($.fn.select2)) {
      throw 'select2 is not defined'
    }

    target.select2({
      ajax: {
        url: target.data('url'),
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
