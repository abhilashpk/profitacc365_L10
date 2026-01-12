<?php
require_once $path;
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Profit ACC 365 ERP - Design Report Preview</title>

	<!-- Report Office2013 style -->
	<link href="{{asset('css/stimulsoft.viewer.office2013.whiteteal.css')}}" rel="stylesheet">

	<!-- Stimusloft Reports.JS -->
	<script src="{{asset('scripts/stimulsoft.reports.js')}}" type="text/javascript"></script>
	<script src="{{asset('scripts/stimulsoft.viewer.js')}}" type="text/javascript"></script>
	
	<?php 
		$options = StiHelper::createOptions();
		$options->handler = "../../../handler.php";
		$options->timeout = 30;
		StiHelper::initialize($options);
	?>
	<script type="text/javascript">
		var options = new Stimulsoft.Viewer.StiViewerOptions();
		options.appearance.fullScreenMode = true;
		options.toolbar.showSendEmailButton = true;
		
		var viewer = new Stimulsoft.Viewer.StiViewer(options, "StiViewer", false);
		
		// Process SQL data source
		viewer.onBeginProcessData = function (event, callback) {
			event.connectionString = 'Server=localhost;Database={{env('DB_DATABASE')}};uid={{env('DB_USERNAME')}};password={{env('DB_PASSWORD')}};';
			<?php StiHelper::createHandler(); ?>
		}
		
		// Manage export settings on the server side
		viewer.onBeginExportReport = function (args) {
			<?php //StiHelper::createHandler(); ?>
			//args.fileName = "MyReportName";
		}
		
		// Process exported report file on the server side
		/*viewer.onEndExportReport = function (event) {
			event.preventDefault = true; // Prevent client default event handler (save the exported report as a file)
			<?php StiHelper::createHandler(); ?>
		}*/
		
		// Send exported report to Email
		viewer.onEmailReport = function (event) {
			<?php StiHelper::createHandler(); ?>
		}
		
		// Load and show report
		var report = new Stimulsoft.Report.StiReport(); var view = '{{$view}}';
		report.loadFile("{{asset('reports/')}}/"+view); var id; //Numak1.mrt Numak2.mrt Numak3.mrt
		
		
		var parts = window.location.href.split("/");
		var qryPara = parts[parts.length - 2];
		//console.log(qryPara);
		/* function getUrlVars() {
			var vars = {};
			var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi,
				function (m, key, value) {
					vars[key] = value;
			});
			return vars;
		}
		var vars = getUrlVars();
		console.log(vars);
		report.dictionary.variables.list.forEach(function(item, i, arr) {
			if (typeof vars[item.name] != "undefined") item.valueObject = vars[item.name];
		});
		 */
		 
		 
		 report.dictionary.variables.getByName("id").valueObject = qryPara;
		//report.setVariable(id, 15);
		viewer.report = report;
		
		function onLoad() {
			viewer.renderHtml("viewerContent");
		}
	</script>
	</head>
<body onload="onLoad();">
	<div id="viewerContent"></div>
</body>
</html>
