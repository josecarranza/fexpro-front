/* var tf = new TableFilter('demo', {
    base_path: 'dist/tablefilter/',
    extensions: [{ name: 'filtersVisibility' }]
});
tf.init(); */


var filtersConfig = {
        base_path: 'dist/tablefilter/',
		sticky_headers: true,
		filters_cell_tag: "th",
        paging: {
          results_per_page: ['Records: ', [100, 300, 500, 1000, 1500, 2000, 3000, 4000, 5000]]
        },
        alternate_rows: true,
        btn_reset: true,
        rows_counter: true,
		col_0: 'select',
    };
    var tf = new TableFilter('demo', filtersConfig);
    tf.init();