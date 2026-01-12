<?php
require_once 'stimulsoftV2/helper.php';
$reportName = isset($_GET['report']) ? basename($_GET['report']) : 'SimpleList.mrt';
$reportNameWithoutExt = preg_replace('/\.mrt$/', '', $reportName);
?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <title>Stimulsoft Reports.PHP - Designer - <?php echo $reportNameWithoutExt; ?></title>
    <style>html, body { font-family: sans-serif; }</style>

    <!-- Office2013 White-Blue style -->
    <link href="css/stimulsoft.viewer.office2013.whiteblue.css" rel="stylesheet">
    <link href="css/stimulsoft.designer.office2013.whiteblue.css" rel="stylesheet">

    <!-- Stimulsoft Reports.JS -->
    <script src="scripts/stimulsoft.reports.js"></script>
    <script src="scripts/stimulsoft.viewer.js"></script>
    <script src="scripts/stimulsoft.designer.js"></script>
    <script src="scripts/stimulsoft.blockly.js"></script>

    <?php StiHelper::init('handler.php', 30); ?>

    <script type="text/javascript">
        var options = new Stimulsoft.Designer.StiDesignerOptions();
        options.appearance.fullScreenMode = true;
        var designer = new Stimulsoft.Designer.StiDesigner(options, "StiDesigner", false);

        // Initialize with the filename from the URL (or default)
        var currentReportName = '<?php echo $reportName; ?>';

        // When opening a report via the Designer UI
        designer.onLoadReport = function (args) {
            var fileName = prompt("Enter a name to save this report:", currentReportName.replace('.mrt', ''));
            if (fileName) {
                currentReportName = fileName.endsWith('.mrt') ? fileName : fileName + '.mrt';
                document.title = "Stimulsoft Reports.PHP - Designer - " + fileName; // Update tab title
            }
        };

        // When saving the report
        designer.onSaveReport = function (args, callback) {
            args.fileName = currentReportName.replace('.mrt', '');
            args.reportJson = designer.report.saveToJsonString();
            Stimulsoft.Helper.process(args, callback);
        };

        // Load the initial report
        var report = new Stimulsoft.Report.StiReport();
        report.loadFile("reports/<?php echo $reportName; ?>"); // Use the URL parameter
        designer.report = report;

        function onLoad() {
            designer.renderHtml("designerContent");
        }
    </script>
</head>
<body onload="onLoad();">
    <div id="designerContent"></div>
</body>
</html>
