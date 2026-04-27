$(document).ready(function() {
    if ($.fn.DataTable) {
        $.extend(true, $.fn.dataTable.defaults, {
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100],
            autoWidth: false,
            scrollX: true,
            language: {
                paginate: {
                    previous: "<i class='mdi mdi-chevron-left'>",
                    next: "<i class='mdi mdi-chevron-right'>"
                }
            },
            drawCallback: function() {
                $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
            }
        });
    }

    // Auto-init admin tables (opt-in via class, or any dt-responsive table)
    $('table.js-admin-datatable, table.dt-responsive').each(function() {
        if ($.fn.DataTable.isDataTable(this)) return;
        if ($(this).data('no-dt') === 1 || $(this).attr('data-no-dt') === '1') return;
        // Skip tables that are explicitly initialized later in this file
        var explicitIds = [
            'basic-datatable',
            'datatable-buttons',
            'selection-datatable',
            'key-datatable',
            'alternative-page-datatable',
            'scroll-vertical-datatable',
            'scroll-horizontal-datatable',
            'complex-header-datatable',
            'row-callback-datatable',
            'state-saving-datatable'
        ];
        if (this.id && explicitIds.indexOf(this.id) !== -1) return;
        $(this).DataTable({ order: [[0, "desc"]] });
    });

    $("#basic-datatable").DataTable({ order: [[0, "desc"]] });
    var a = $("#datatable-buttons").DataTable({
        lengthChange: 1,
        buttons: [{
            extend: "copy",
            className: "btn-light"
        }, {
            extend: "print",
            className: "btn-light"
        }, {
            extend: "pdf",
            className: "btn-light"
        }],
    });
    $("#selection-datatable").DataTable({
        select: {
            style: "multi"
        }
    }), $("#key-datatable").DataTable({
        keys: !0,
    }), a.buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)"), $("#alternative-page-datatable").DataTable({
        pagingType: "full_numbers",
    }), $("#scroll-vertical-datatable").DataTable({
        scrollY: "350px",
        scrollCollapse: !0,
        paging: !1,
    }), $("#scroll-horizontal-datatable").DataTable({
        scrollX: !0,
    }), $("#complex-header-datatable").DataTable({
        columnDefs: [{
            visible: !1,
            targets: -1
        }]
    }), $("#row-callback-datatable").DataTable({
        createdRow: function(a, e, i) {
            15e4 < +e[5].replace(/[\$,]/g, "") && $("td", a).eq(5).addClass("text-danger")
        }
    }), $("#state-saving-datatable").DataTable({
        stateSave: !0,
    })
});
