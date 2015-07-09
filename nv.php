<?php
include('fund.config.php');
include('session.php');

if (isset($_GET["fundId"])) {
  $fundId = $_GET["fundId"];
  if (!check_and_select_fundId($fundId, $g_authFunds)) {
    die("not authrized");
  }
}
$fundId=$_SESSION['fundId'];
$selectHtml = "<select id='fundSel'>";
foreach($g_authFunds as $authFundId) {
  if (array_key_exists($authFundId, $g_funds)) {
    $selected = "";
    if ($fundId == $authFundId) {
      $selected = " selected";
    }
    $selectHtml .= "<option value='" . $authFundId . "'" . $selected . ">" . $g_funds[$authFundId] . "</option>";
  }
}
$selectHtml .= "</select>";
?>
<html>
  <head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>净值录入</title>
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/jquery.handsontable.full.js"></script>
	<script type="text/javascript" src="js/jquery-ui/js/jquery-ui.custom.min.js"></script>
	<script type="text/javascript" src="js/spin.min.js"></script>
	<script type="text/javascript" src="js/jquery.spin.js"></script>
	<link rel="stylesheet" href="js/jquery.handsontable.full.css">
	<link rel="stylesheet" href="js/jquery-ui/css/ui-bootstrap/jquery-ui.custom.css">
	<link rel="stylesheet" href="css.css">
  </head>
  <body>
	<div class="content" style="width:400px">
	  <div id="divOverlay"><div id="divSpin"></div></div>
	  <div id="cmdBar">
		<div style="float:right">
		  <button name="add">添加</button>
		  <button name="del">删除</button>
		</div>
		切换基金:
		<?php echo $selectHtml ?>
		  <button name="change_fund">确定</button>
		  <span id="console"></span>
		</div>
      </div>
	  <div id="datatable" class="handsontable" style="width:400px"></div>
	  <div id="dialog-confirm" title="Confirm" style="display:none">
	    <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>确认删除？</p>
  	  </div>
	  <div id="dialog-message"></div>
    </div>
	
	<script>
	  $(window).load(function() {
	  $("#datatable").css({height: ($('body').height() - 50)+'px'});
	  });
	  $(function() {
	  //////////////////////////////////////////////////////////////////////////////////////
	  // spin
	  //////////////////////////////////////////////////////////////////////////////////////
	  function startSpin() {
	  $('#divSpin').spin({
	  lines: 7, // The number of lines to draw
	  length: 0, // The length of each line
	  width: 13, // The line thickness
	  radius: 15, // The radius of the inner circle
	  corners: 1, // Corner roundness (0..1)
	  rotate: 1, // The rotation offset
	  direction: 1, // 1: clockwise, -1: counterclockwise
	  color: '#000', // #rgb or #rrggbb or array of colors
	  speed: 1.4, // Rounds per second
	  trail: 58, // Afterglow percentage
	  shadow: false, // Whether to render a shadow
	  hwaccel: false, // Whether to use hardware acceleration
	  className: 'spinner', // The CSS class to assign to the spinner
	  zIndex: 2e9, // The z-index (defaults to 2000000000)
	  top: '50%', // Top position relative to parent
	  left: '50%' // Left position relative to parent
	  });
	  $("#divOverlay").show();
	  }

	  function stopSpin() {
	  $('#divSpin').spin(false);
	  $("#divOverlay").hide();
	  }


	  //////////////////////////////////////////////////////////////////////////////////////
	  // showMessage
	  //////////////////////////////////////////////////////////////////////////////////////

	  function showMessage(msg) {
	  $( "#dialog-message" ).html(msg).dialog({
	  modal: true,
	  buttons: {
	  Ok: function() {
	  $( this ).dialog( "close" );
	  }
	  }
	  });
	  }
	  
	  //////////////////////////////////////////////////////////////////////////////////////
	  // datatable
	  //////////////////////////////////////////////////////////////////////////////////////
	  var $container = $("#datatable");
	  var $console = $("#console");
	  var $parent = $container.parent();
	  var allData = null;
	  var colHeaders = {
	  colId: ["select", "id", "nv_date", "net_value", "ref_value"],
	  colName: ["", "id", "日期", "净值", "对比指数"],
	  width: [15,30,60,50,50]
	  }
	var colIdToColMap = {};
	var colToColIdMap = {};
	for (var i=0; i<colHeaders.colId.length; i++) {
	  var colId = colHeaders.colId[i];
	  colIdToColMap[colId] = i;
	  colToColIdMap[i] = colId;
	}

	var calcRenderer = function (instance, td, row, col, prop, value, cellProperties) {
	  Handsontable.renderers.TextRenderer.apply(this, arguments);
	  $(td).css({
		background: '#EFEFEF'
	  });
	};

	// create handsontable
	$container.handsontable({
	  colHeaders: colHeaders.colName,
	  colWidths: colHeaders.width,
	  columns: [
		{type: 'checkbox'},
		{readOnly: true}, // id
		{type: 'date', dateFormat: 'yymmdd'},
		{type: 'numeric', format:'0.000000'},
		{type: 'numeric', format:'0.00'}
	  ],
	  startRows: 1,
	  //rowHeaders: true,
	  minSpareRows: 0,
	  columnSorting: true,
	  manualColumnMove: false,
	  manualColumnResize: true,
	  fillHandle: false,
	  stretchH: 'all',
	  currentRowClassName: 'currentRow',
	  afterChange: function (changes, source) {
		var handsontable = this;
		if (source === 'loadData') {
		  if (handsontable.sortingEnabled) {
			handsontable.sort(handsontable.mySortColumn,handsontable.mySortOrder);
		  }
		  return; //don't save this change
		}
		
		if (!Array.isArray(changes[0])) {
		  var change = changes.slice();
		  changes = [];
		  changes.push(change);
		}
		if (changes[0][1] == undefined || changes[0][1] == 0) {
		  return; // don't handle checkbox change
		}
		if (changes.length >= 2) {
		  startSpin();
		}
		var changedItems = [];
		for (var i=0; i<changes.length; i++) {
		  var change = changes[i];
		  if (change == null)
			continue;
		  var col = change[1];
		  if (col == colIdToColMap["select"]) {
			continue;
		  }
		  var id = handsontable.getDataAtCell(change[0],colIdToColMap["id"]);
		  var val = change[3];
		  changedItems.push({"id": id, "col": colHeaders.colId[col], "val": val});
		}
		if (changedItems.length == 0) {
		  stopSpin();
		  return;
		}
		$.ajax({
		  url: "save.php",
		  dataType: "json",
		  type: "POST",
		  data: {"data":changedItems}
		}).done(function (res) {
		  if (res.code != 0) {
			$console.text("Autosave failed. err: " + res.err);
			showMessage("Autosave failed. err: " + res.err);
		  } else {
			$console.text('Autosaved (' + changes.length + ' ' +
						  'cell' + (changes.length > 1 ? 's' : '') + ')');
		  }
		}).fail(function () {
		  showMessage('Autosave failed. ');
		  $console.text('Autosave failed. ');
		}).always(function() {
		  if (changes.length >= 2) {
			stopSpin();
		  }
		});
	  },
	});
	var handsontable = $container.handsontable('getInstance');

	function setHandsontableData(data) {
	  if (typeof handsontable.sortColumn == 'undefined' || handsontable.sortOrder == 'undefined') {
		handsontable.mySortColumn = colIdToColMap["id"];
		handsontable.mySortOrder = false;
	  } else {
		handsontable.mySortColumn = handsontable.sortColumn;
		handsontable.mySortOrder = handsontable.sortOrder;
	  }
	  handsontable.loadData(data);
	}
	
	function load() {
	  startSpin();
      $console.text('Loading...');
      $.ajax({
		url: "load_web.php",
		dataType: 'json',
		type: 'GET'
	  }).done(function (res) {
		allData = res.data;
		setHandsontableData(allData);
        $console.text(allData.length + ' records loaded.');
	  }).fail(function (jqXHR, textStatus, errorThrown) {
		showMessage('Load failed. ');
        $console.text('Load failed. ');
		//console.log(textStatus);
		console.log(errorThrown);
	  }).always(function() {
		stopSpin();
      });
	}
	
	function getSelectedIds() {
	  var selected = handsontable.getDataAtCol(colIdToColMap["select"]);
	  var ids = handsontable.getDataAtCol(colIdToColMap["id"]);
	  var selectedIds = [];
	  for (var i=0; i<selected.length; i++) {
		if (selected[i]) {
		  selectedIds.push(ids[i]);
		}
	  }
	  return selectedIds;
	}

	$parent.find('button[name=change_fund]').click(function(){
	  location.href = "nv.php?fundId=" + $("#fundSel").val();
	  return false;
	});

	// add
	$parent.find('button[name=add]').click(function(){
	  startSpin();
      $console.text('Adding...');
      $.ajax({
		url: "add_web.php",
		dataType: 'json',
		type: 'POST'
	  }).done(function (res) {
        if (res.code === 0) {
		  allData.push.apply(allData, res.data);
		  setHandsontableData(allData);
		  $console.text('' + res.data.length + ' record' + ((res.data.length>1)?'s':'') + ' added.');
        } else {
		  $console.text('Add error. ' + res.err);
		  showMessage('Add error. ' + res.err);
        }
	  }).fail(function () {
        $console.text('Add error. ');
		showMessage('Add error. ');
	  }).always(function() {
		stopSpin();
      });
	});
	
	$parent.find('button[name=del]').click(function () {
	  var ids = getSelectedIds();
	  if (ids.length == 0) {
		showMessage('Select one first');
		return;
	  }
	  $( "#dialog-confirm" ).dialog({
		resizable: false,
		height:200,
		modal: true,
		buttons: {
		  "Confirm": function() {
			startSpin();
			$console.text('Delete selected stocks...');
			$.ajax({
			  url: "del.php",
			  data: {"data": ids},
			  dataType: 'json',
			  type: 'POST',
			  success: function (res) {
				if (res.code === 0) {
				  var deletedIds = res.data;
				  var idCol = colIdToColMap["id"];
				  allData = allData.filter(function(item) {
					return deletedIds.indexOf(item[idCol]) < 0;
				  });
				  setHandsontableData(allData);
				  stopSpin();
				  $console.text('' + deletedIds.length + ' record' + ((deletedIds.length>1)?'s':'') + ' deleted.');
				}
				else {
				  stopSpin();
				  $console.text('Delete error. ' + res.err);
				  showMessage('Delete error. ' + res.err);
				}
			  },
			  error: function () {
				$console.text('Delete error. ');
				showMessage('Delete error. ');
			  }
			});
			$( this ).dialog( "close" );
		  },
		  Cancel: function() {
			$( this ).dialog( "close" );
		  }
		}
	  });
	});

	// load data
	load();
  });
	</script>
  </body>
</html>
