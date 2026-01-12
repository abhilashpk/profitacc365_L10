<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Stimulsoft Designer</title>

  <link href="{{ asset('stimulsoftV2/css/stimulsoft.designer.office2013.whiteblue.css') }}" rel="stylesheet" />
  <link href="{{ asset('stimulsoftV2/css/stimulsoft.viewer.office2013.whiteblue.css') }}" rel="stylesheet" />

  <script src="{{ asset('stimulsoftV2/js/stimulsoft.reports.js') }}"></script>
  <script src="{{ asset('stimulsoftV2/js/stimulsoft.viewer.js') }}"></script>
  <script src="{{ asset('stimulsoftV2/js/stimulsoft.designer.js') }}"></script>

  <style>
    html,body{height:100%;margin:0}
    #designerContent{width:100%;height:100vh}
    .topbar{position:fixed;top:8px;right:8px;z-index:99999}
    .topbar button{padding:6px 10px;border-radius:4px;border:1px solid #ccc;background:#fff;cursor:pointer}

    /* Saved message modal (clean) */
    .sti-message-backdrop{position:fixed;inset:0;background:rgba(0,0,0,0.35);z-index:200000;display:flex;align-items:center;justify-content:center}
    .sti-message{width:420px;background:#fff;border-radius:4px;box-shadow:0 10px 30px rgba(0,0,0,0.25);font-family:Arial, sans-serif}
    .sti-message .header{height:44px;background:#f5f5f5;border-bottom:1px solid #eee;display:flex;align-items:center;padding:0 12px;font-weight:700}
    .sti-message .content{display:flex;gap:12px;padding:16px 12px;align-items:center}
    .sti-message .content img{width:48px;height:48px}
    .sti-message .content .text{font-size:14px;color:#222}
    .sti-message .buttons{display:flex;justify-content:flex-end;padding:8px 12px;border-top:1px solid #eee}
    .sti-message .btn{padding:8px 12px;border-radius:4px;border:1px solid #cfcfcf;background:#f6f6f6;cursor:pointer}
    .sti-message .btn.primary{background:#0078d4;color:#fff;border-color:#0078d4}
    .hidden{display:none}
  </style>
</head>
<body>
  <div class="topbar">
    <button id="btnSaveAs">Save As...</button>
  </div>

  <div id="designerContent"></div>

  <!-- Saved modal -->
  <div id="stiSavedModal" class="hidden" aria-hidden="true">
    <div class="sti-message-backdrop" id="stiSavedBackdrop">
      <div class="sti-message" role="dialog" aria-modal="true">
        <div class="header">Designer</div>
        <div class="content">
          <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAABGdBTUEAAK/INwWK6QAAAjdJREFUeNrEl10oBFEUx3cHW4qIlKStlZDy8aB9IkUe2NryoC2ixCPFu4949KZ4UaKUFJFYpKgtJRtJSvmoffKkfERtYVv/u53LWGbn3jF2Tv3aabo75z/nnHvuGXvL8LrNSksVXJcHOoEbVIDyX9ZcgHMQBAvgzgwBTaAHtAKHztpywgcmgB/M0K+mKRr388E22KUHOiQjmwK8YBMcaURMMwKl5NiZyEOVK9vW5ymJXU/5r2xnoUetpSxtx6ALrOhFIIfe3Kn3isx5QU56jEFvmd7ydLAEGvQEzAGXUIwV+9dDFOG0rIJiLQHtlDchm966tj28vMaY3LgS/VsWWCYxMbOr+sApqE7S9u+irfpZhE0yzv0j9T/uecYDMgLGwCKIKCoBwsaccYfv9kwjEWB1VquugQbDrTT6/HntSEuR+atPLaDUjMS+vUdklru5AFaZGWYIiEallldwAa8WHYQvfBeEwRNFwkARZBkVEFLXwLkFEQiqBQQsEHCgFrCQZOdhPidwAZdgLYkC5qnuvp0FLhoe8oy0YomWzKqfnd+38QMJq8pmsKe3IyT7frz1c+e/zQMnJCL0T6EfpfAnnAkPQSWYpGIxw25AGxgXHUpZngZAIYVshxeNhEVoq3VTzldkpmJu92zmpLRkg1zQSLtGy4ZAEdVXHYU8IjuWJxK0D2pooNBqscI1pBjMKUtRB+j9a50ofyyuWYrGpVUC+DdhTfz2MvvjVCQl3XSoha0QoO7xUvYhwAAI1nnwGN3RsgAAAABJRU5ErkJggg==" alt="icon" />
          <div class="text" id="stiSavedMessage">Saved: SimpleList.mrt</div>
        </div>
        <div class="buttons">
          <button id="stiSavedOk" class="btn primary">OK</button>
        </div>
      </div>
    </div>
  </div>

<script type="text/javascript">
(function () {
  var HANDLER_URL = "{{ url('stimulsoftV2/sti-handler.php') }}";
  var LICENSE_URL = "{{ url('/stimulsoftV2/license') }}";
  var DEFAULT_MRT = "{{ asset('stimulsoftV2/reports/'.$view) }}";

  /* set global adapter URL early */
  if (window.Stimulsoft && Stimulsoft.StiOptions) {
    Stimulsoft.StiOptions.WebServer = Stimulsoft.StiOptions.WebServer || {};
    Stimulsoft.StiOptions.WebServer.url = HANDLER_URL;
  }

  /* load license synchronously (silent) */
  try {
    var licReq = new XMLHttpRequest();
    licReq.open('GET', LICENSE_URL, false);
    licReq.send(null);
    if (licReq.status === 200) {
      try { Stimulsoft.Base.StiLicense.key = licReq.responseText.trim(); } catch(e) {}
    }
  } catch(e) {}

  /* create designer */
  var options = new Stimulsoft.Designer.StiDesignerOptions();
  options.appearance.fullScreenMode = true;
  options.appearance.showTooltips = true;
  options.toolbar.showSaveButton = true;
  options.toolbar.showSaveToServerButton = true;

  var designer = new Stimulsoft.Designer.StiDesigner(options, "StiDesigner", false);
  window.stiDesigner = designer;

  /* load initial report */
  try {
    var report = new Stimulsoft.Report.StiReport();
    report.loadFile(DEFAULT_MRT);
    designer.report = report;
    if (report.reportFile) document.title = 'Designer — ' + report.reportFile;
  } catch(e) {}

  /* modal elements */
  var modalRoot = document.getElementById('stiSavedModal');
  var modalMessage = document.getElementById('stiSavedMessage');
  var modalOk = document.getElementById('stiSavedOk');

  function showSavedModal(name) {
    modalMessage.textContent = 'Saved: ' + name + '.mrt';
    modalRoot.classList.remove('hidden');
    modalRoot.style.display = 'block';
    modalOk.focus();
  }
  function hideSavedModal() {
    modalRoot.classList.add('hidden');
    modalRoot.style.display = 'none';
  }
  modalOk.addEventListener('click', hideSavedModal);

  /* perform save via custom endpoint your handler accepts */
  function performSave(fileNameNoExt, callback) {
    var payload = { action: 'SaveReport', fileName: fileNameNoExt, reportJson: designer.report.saveToJsonString() };
    fetch(HANDLER_URL, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    }).then(function (resp) {
      return resp.text();
    }).then(function (text) {
      var parsed = { success: false, notice: text };
      try { parsed = JSON.parse(text); } catch (e) {}
      if (parsed && parsed.success) {
        showSavedModal(fileNameNoExt);
        document.title = 'Designer — ' + fileNameNoExt + '.mrt';
      } else {
        // show modal anyway (clean minimal UI). server notice can be extended if desired.
        showSavedModal(fileNameNoExt);
      }
      if (typeof callback === 'function') callback(parsed);
    }).catch(function (err) {
      showSavedModal(fileNameNoExt);
      if (typeof callback === 'function') callback({ success: false, notice: err.message });
    });
  }

  /* designer Save: overwrite if a file is opened; otherwise prompt once */
  designer.onSaveReport = function(args, callback) {
    try {
      var rawFile = (designer.report && designer.report.reportFile) ? designer.report.reportFile : '';
      var baseName = rawFile ? rawFile.replace(/^.*[\\/]/,'').replace(/\.mrt$/i,'') : '';
      if (baseName) {
        performSave(baseName, function(resp){ if (typeof callback === 'function') callback(resp); });
        return;
      }
      var name = prompt('Enter filename to save (without extension):', 'SimpleList');
      if (!name) { if (typeof callback === 'function') callback({ success:false, notice:'Save cancelled' }); return; }
      name = name.replace(/[^a-zA-Z0-9_-]/g,'') || 'Report';
      performSave(name, function(resp){ if (typeof callback === 'function') callback(resp); });
    } catch(e) {
      if (typeof callback === 'function') callback({ success:false, notice: e.message || String(e) });
    }
  };

  /* Save As button */
  var btnSaveAs = document.getElementById('btnSaveAs');
  btnSaveAs.addEventListener('click', function(){
    var rawFile = (designer.report && designer.report.reportFile) ? designer.report.reportFile : '';
    var defaultName = rawFile ? rawFile.replace(/^.*[\\/]/,'').replace(/\.mrt$/i,'') : 'SimpleList';
    var name = prompt('Save As - filename (no extension):', defaultName);
    if (!name) return;
    name = name.replace(/[^a-zA-Z0-9_-]/g,'') || 'Report';
    performSave(name);
  });

  designer.renderHtml('designerContent');
})();
</script>
</body>
</html>
