"use strict";

$(document).ready(function() {

    $('#table1').DataTable({
        "scrollX": true
    });
    $('#table2').DataTable({
        "dom": '<"m-t-10 pull-left"l><"m-t-10 pull-right"f>rt<"pull-left m-t-10"i><"m-t-10 pull-right"p>'
    });
    $('#table3').DataTable({
        "dom": '<"pull-left m-t-10"f><"pull-right  m-t-10"i><"clearfix m-t-10"><"pull-left m-t-10"l><"pull-right m-t-10"p>rt<"pull-left m-t-10"l><"pull-right m-t-10"p><"clearfix m-t-10"><"pull-left m-t-10"f><"pull-right m-t-10"i>'
    });
    $('#table4').DataTable({
        mark: true
    });

		
    window.onload = function() {
		
        $(function() {
            var inputMapper = {
                "account_id": 1,
                "master_name": 2,
                "group": 3,
                "category": 4
            };
            var dtInstance = $("#tableAcmaster").DataTable({
                "lengthMenu": [10, 25, 50, "ALL"],
                bLengthChange: false,
                mark: true,
				"aoColumns": [null,null,null,null,null,null,{ "bSortable": false },{ "bSortable": false },{ "bSortable": false } ],
				//"scrollX": true,
            });
            $("input").on("input", function() {
                var $this = $(this);
                var val = $this.val();
                var key = $this.attr("name");
                dtInstance.columns(inputMapper[key] - 1).search(val).draw();
            });
        });
		
		
		
		$(function() {
            
            var dtInstance = $("#tableAcgroup").DataTable({
                "lengthMenu": [10, 25, 50, "ALL"],
                bLengthChange: false,
                mark: true,
				"bSort" : false,
				"aoColumns": [null,null,{ "bSortable": false },{ "bSortable": false } ],
				//"scrollX": true,
            });
            
        });
		
		
		
		$(function() {
            
            var dtInstance = $("#tableItemEnq").DataTable({
                "lengthMenu": [10, 25, 50, "ALL"],
                bLengthChange: false,
                mark: true,
				"aoColumns": [null,null,null,null,null,null,null,null,{ "bSortable": false },{ "bSortable": false },{ "bSortable": false } ],
				//"scrollX": true,
            });
            
        });
		
		$(function() {
            var inputMapper = {
                "voucher_type": 1,
                "voucher_name": 2
            };
            var dtInstance = $("#tableAcsettings").DataTable({
                "lengthMenu": [10, 25, 50, "ALL"],
                bLengthChange: false,
                mark: true,
				"aaSorting": [[0, "desc"]],
				"aoColumns": [null,null,null,null,null,{ "bSortable": false },{ "bSortable": false } ],
				//"scrollX": true,
            });
            $("input").on("input", function() {
                var $this = $(this);
                var val = $this.val();
                var key = $this.attr("name");
                dtInstance.columns(inputMapper[key] - 1).search(val).draw();
            });
        });
		
		$(function() {
            var inputMapper = {
                "voucher_type": 1,
                "voucher_name": 2
            };
            var dtInstance = $("#tableTransaction").DataTable({
                "lengthMenu": [10, 25, 50, "ALL"],
                bLengthChange: false,
                mark: true,
				"aaSorting": [[0, "desc"]],
				"aoColumns": [null,null,null,null,{ "bSortable": false },{ "bSortable": false },{ "bSortable": false } ],
				//"scrollX": true,
            });
            $("input").on("input", function() {
                var $this = $(this);
                var val = $this.val();
                var key = $this.attr("name");
                dtInstance.columns(inputMapper[key] - 1).search(val).draw();
            });
        });
		
		/* $(function() {
            var inputMapper = {
                "voucher_type": 1,
                "voucher_name": 2
            };
            var dtInstance = $("#tablePorders").DataTable({
                "lengthMenu": [10, 25, 50, "ALL"],
                bLengthChange: false,
                mark: true,
				"aoColumns": [null,null,null,{"sType": "date"},null,{ "bSortable": false },{ "bSortable": false },{ "bSortable": false } ],
				"aaSorting": [],
				//"order": [[ 1, "desc" ]]
				//"scrollX": true,
            });
            $("input").on("input", function() {
                var $this = $(this);
                var val = $this.val();
                var key = $this.attr("name");
                dtInstance.columns(inputMapper[key] - 1).search(val).draw();
            });
        }); */
		
		$(function() {
            var inputMapper = {
                "voucher_type": 1,
                "voucher_name": 2
            };
            var dtInstance = $("#tableSalesInvoice").DataTable({
                "lengthMenu": [10, 25, 50, "ALL"],
                bLengthChange: false,
                mark: true,
				"aoColumns": [null,null,{"sType":"date"},null,null,{ "bSortable": false },{ "bSortable": false },{ "bSortable": false },{ "bSortable": false } ],
				"aaSorting": [],
				//"order": [[ 1, "desc" ]]
				//"scrollX": true,
            });
            $("input").on("input", function() {
                var $this = $(this);
                var val = $this.val();
                var key = $this.attr("name");
                dtInstance.columns(inputMapper[key] - 1).search(val).draw();
            });
        });
		
		
		$(function() {
            var inputMapper = {
                "voucher_type": 1,
                "voucher_name": 2
            };
            var dtInstance = $("#tableReports").DataTable({
                "lengthMenu": [10, 25, 50, "ALL"],
                bLengthChange: false,
                mark: true,
				"aoColumns": [null,null,null,null,null,null,null,null ],
				"aaSorting": [],
				//"order": [[ 1, "desc" ]]
				//"scrollX": true,
            });
            $("input").on("input", function() {
                var $this = $(this);
                var val = $this.val();
                var key = $this.attr("name");
                dtInstance.columns(inputMapper[key] - 1).search(val).draw();
            });
        });
		
		
		$(function() {
            var inputMapper = {
                "voucher_type": 1,
                "voucher_name": 2
            };
            var dtInstance = $("#tableVouchers").DataTable({
                "lengthMenu": [10, 25, 50, "ALL"],
                bLengthChange: false,
                mark: true,
				"aoColumns": [null,null,null,null,null,null,null,{ "bSortable": false },{ "bSortable": false } ],
				"aaSorting": [],
				//"order": [[ 1, "desc" ]]
				//"scrollX": true,
            });
            $("input").on("input", function() {
                var $this = $(this);
                var val = $this.val();
                var key = $this.attr("name");
                dtInstance.columns(inputMapper[key] - 1).search(val).draw();
            });
        });
		
		$(function() {
            var inputMapper = {
                "voucher_type": 1,
                "voucher_name": 2
            };
             var dtInstance = $("#tableInvList").DataTable({
                "lengthMenu": [10, 25, 50, "ALL"],
                bLengthChange: false,
                mark: true,
				"aoColumns": [{ "bSortable": false }, null,null,null,null ],
				"aaSorting": [],
				//"order": [[ 1, "desc" ]]
				//"scrollX": true,
            });
            $("input").on("input", function() {
                var $this = $(this);
                var val = $this.val();
                var key = $this.attr("name");
                dtInstance.columns(inputMapper[key] - 1).search(val).draw();
            });
        });
		
		$(function() {
            var inputMapper = {
                "voucher_type": 1,
                "voucher_name": 2
            };
            var dtInstance = $("#tableLocation").DataTable({
                "lengthMenu": [10, 25, 50, "ALL"],
                bLengthChange: false,
                mark: true,
				"aoColumns": [null,null,null,{ "bSortable": false },{ "bSortable": false },{ "bSortable": false } ],
				"aaSorting": [],
				//"order": [[ 1, "desc" ]]
				//"scrollX": true,
            });
            $("input").on("input", function() {
                var $this = $(this);
                var val = $this.val();
                var key = $this.attr("name");
                dtInstance.columns(inputMapper[key] - 1).search(val).draw();
            });
        });
		
		$(function() {
            
            var dtInstance = $("#tableAcnts").DataTable({
                "lengthMenu": [10, 25, 50, "ALL"],
                bLengthChange: false,
                mark: true,
				"bSort" : false,
				"aoColumns": [null,null],
				//"scrollX": true,
            });
            
        });
		
    }
});
