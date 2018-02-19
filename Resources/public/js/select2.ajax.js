$(document).ready(function () {
  $('.select2.select_ajax').click(function (e) {
    if ($(this).data('url') == '') {
      throw 'url is not defined'
    }
    if (!$.isFunction($.fn.select2)) {
      throw 'select2 is not defined'
    }

    $(this).select2({
      ajax: {
        url: $(this).data('url'),
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
  });
});