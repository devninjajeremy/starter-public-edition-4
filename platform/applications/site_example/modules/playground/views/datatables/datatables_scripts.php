<?php defined('BASEPATH') OR exit('No direct script access allowed');

echo js('lib/dataTables/jquery.dataTables.min.js');
echo js('lib/dataTables/plug-ins/api/sortNeutral.js');
echo js('lib/dataTables/dataTables.bootstrap.js');
echo js('lib/dataTables/datatables.responsive.js');

?>

    <script type="text/javascript">
    //<![CDATA[

    $(function() {

        var responsiveHelper;
        var breakpointDefinition = {
            tablet: 1024,
            phone : 480
        };

        var tableElement = $('#datatable');

        var table = tableElement.DataTable({
            'orderCellsTop': true,
            'pagingType': 'simple_numbers',
            'stateSave': true,
            'columnDefs': [{
                'targets': [3, 4, 5],
                'searchable': false,
                'orderable': false
             }],
            'language': <?php echo $this->lang->datatables(); ?>,
            // Making the table responsive.
            'autoWidth': false,
            'preDrawCallback': function () {
                // Initialize the responsive datatables helper once.
                if (!responsiveHelper) {
                    responsiveHelper = new ResponsiveDatatablesHelper(tableElement, breakpointDefinition);
                }
            },
            'rowCallback': function (nRow) {
                responsiveHelper.createExpandIcon(nRow);
            },
            'drawCallback': function (oSettings) {
                responsiveHelper.respond();
            },
            // See http://odoepner.wordpress.com/2011/12/12/jquery-datatables-column-filters-state-saving/
            'initComplete': function(oSettings, json) {
                var cols = oSettings.aoPreSearchCols;
                for (var i = 0; i < cols.length; i++) {
                    var value = cols[i].sSearch;
                    if (value.length > 0) {
                        if ($('thead input[type=text]').length) {
                            if (typeof $('thead input[type=text]')[i] != 'undefined') {
                                $('thead input[type=text]')[i].value = value;
                            }
                        }
                    }
                }
            }
        });

        // Individual text-input filters.

        $("#datatable thead input[type=text]").on('keyup change', function () {
 
            table
                .column($(this).parent().index() + ':visible')
                .search(this.value)
                .draw();
        });

        // Clear sort.
        $('#clear_sort').on('click', function() {

            table.sortNeutral();
        });

        // Clear search.
        $('#clear_search').on('click', function() {

            table.column(0).search('');
            $('#search_id').val('');

            table.column(1).search('');
            $('#search_iso_code').val('');

            table.column(2).search('');
            $('#search_country_name').val('');

            table.search('');
            $('#datatable_filter input').val('');

            table.draw();
        });

    });

    //]]>
    </script>
