<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <title>Stimulsoft Report Viewer</title>

  <link href="{{ asset('stimulsoftV2/css/stimulsoft.viewer.office2013.whiteblue.css') }}" rel="stylesheet" />
  <script src="{{ asset('stimulsoftV2/js/stimulsoft.reports.js') }}"></script>
  <script src="{{ asset('stimulsoftV2/js/stimulsoft.viewer.js') }}"></script>
</head>
<body>
  <div id="viewerContent" style="width:100%; height:100%;"></div>

  <script type="text/javascript">
    (async function () {
      const HANDLER_URL = "{{ url('stimulsoftV2/sti-handler.php') }}"; // <-- must match your handler
      const LICENSE_URL = "{{ url('/stimulsoftV2/license') }}";
      const REPORT_FILE = "{{ asset('stimulsoftV2/reports/'.$view) }}";

      // 1) Load license (non-blocking if you prefer — but we await here)
      try {
        const resp = await fetch(LICENSE_URL, { cache: 'no-store' });
        if (resp.ok) {
          Stimulsoft.Base.StiLicense.key = (await resp.text()).trim();
        }
      } catch (e) {
        // silent fail — viewer may still work in trial or local mode
      }

      // 2) IMPORTANT: set the server adapter URL BEFORE creating viewer/report
      if (window.Stimulsoft && Stimulsoft.StiOptions) {
        Stimulsoft.StiOptions.WebServer = Stimulsoft.StiOptions.WebServer || {};
        Stimulsoft.StiOptions.WebServer.url = HANDLER_URL;
        Stimulsoft.StiOptions.WebServer.timeout = 30; // seconds (optional)
      }

      // 3) Create viewer, load report and render
      const viewer = new Stimulsoft.Viewer.StiViewer(null, "StiViewer", false);
      const report = new Stimulsoft.Report.StiReport();

      
      // loadFile returns a Promise in newer Stimulsoft builds; use try/catch to handle failures
      try {
        await report.loadFile(REPORT_FILE);

        var parts = window.location.href.split("/");
	  var qryPara = parts[parts.length - 2];
      report.dictionary.variables.getByName("id").valueObject = qryPara;
      //console.log('hi'+qryPara);

        viewer.report = report;
        viewer.renderHtml("viewerContent");
      } catch (err) {
        // If the report requires server-side data, the handler will be called.
        // Show a simple error message in-page if report load fails.
        document.getElementById('viewerContent').innerText = 'Failed to load report: ' + (err && err.message ? err.message : err);
      }
    })();
  </script>
</body>
</html>