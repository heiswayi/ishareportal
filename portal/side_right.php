<div class="span3">
    
      <div class="box-header">
						<h2><button id="aSFBtn" class="btn btn-mini tip-top" title="Add a sharerlink"><i class="icon-plus"></i></button><span class="break"></span>Sharerlink&trade;</h2>
						<div class="box-icon">
							<a href="#" class="btn-minimize" style="color:#333;"><i class="icon-chevron-up"></i></a>
						</div>
					</div>
					
					<div id="sharerlist" class="box-content"></div>
					
</div>

<div id="addSharerlinkForm" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header hn-color-green">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3 id="myModalLabel">Add sharerlink</h3>
  </div>
  <div class="modal-body">

<div class="alert alert-info"><span class="label label-info">Disclaimer</span> Ishare will not be responsible for whatever you're sharing. Ishare simply provides a service to sharers to put their sharerlink so that it's easily found by the users. Any consequence will reflect to the respective owner.</div>
<div id="asconsole" class="alert alert-error" style="display:none"></div>
<div id="asconsole-success" class="alert alert-success" style="display:none"></div>

<form class="form-horizontal" id="addSharerlink">
  <div class="control-group">
    <label class="control-label" for="inputSharername">Name:</label>
    <div class="controls">
      <input type="text" id="inputSharername" class="sharername" placeholder="Title of sharerlink" value="" maxlength="25">
      <i class="icon-info-sign tip-left" id="sinfo" title="Only alphanumerics, underscore, dot and space are allowed"></i>
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="inputSharerlink">Link:</label>
    <div class="controls">
      <input type="text" id="inputSharerlink" class="sharerlink" placeholder="http://" value="" maxlength="50">
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="inputSharerdesc">Description:</label>
    <div class="controls">
      <textarea id="inputSharerdesc" class="sharerdesc" style="resize:none" rows="4" maxlength="140"></textarea>
    </div>
  </div>
  <input type="hidden" id="currentUser" value="<?php echo $_SESSION['current_userID']; ?>">
</form>

  </div>
  <div id="asFormFooter" class="modal-footer">
    <button id="cancelAdd" class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
    <button id="addSharerlinkBtn" class="btn btn-primary">Submit</button>
  </div>
</div>

<script>
var refreshSharerlink;
var prevState = 0;
var clearTimer = false;
var prevStateCycle = false;

$(document).ready(function () { // START DOCUMENT.READY

initSharerlink();
$("#aSFBtn").click(function (e) {
  e.preventDefault();
  $("#addSharerlinkForm").modal("show");
});
$("#addSharerlinkBtn").click(function (e) {
  e.preventDefault();
  var userID = $("#currentUser").val();
  var sharername = $("#inputSharername").val();
  var sharerlink = $("#inputSharerlink").val();
  var sharerdesc = $("#inputSharerdesc").val();
  var dataString = "add=1&userID=" + urlencode(userID) + "&sharername=" + urlencode(sharername) + "&sharerlink=" + urlencode(sharerlink) + "&sharerdesc=" + urlencode(sharerdesc);
  $.ajax({
    type: "POST",
    url: "sharerlink_add.php",
    data: dataString,
    success: function (html) {
      if (html == "OK") {
        $("#addSharerlink").remove();
        $("#cancelAdd").remove();
        $("#addSharerlinkBtn").remove();
        $("#asFormFooter").html('<button id="closeForm" class="btn" data-dismiss="modal" aria-hidden="true">Close</button>');
        $("#asconsole-success").html('<h4>Success!</h4>Your sharerlink has been added to our database.').fadeIn();
        reloadSharerlink();
      } else {
        $("#asconsole").html(html).fadeIn().delay(5000).fadeOut();
      }
    }
  });
});

}); // END DCOUMENT.READY

function urlencode(a) {
  a = (a + "").toString();
  return encodeURIComponent(a).replace(/!/g, "%21").replace(/'/g, "%27").replace(/\(/g, "%28").replace(/\)/g, "%29").replace(/\*/g, "%2A").replace(/%20/g, "+")
}
function reloadSharerlink() {
  $("#sharerlist").html('<div class="loader" style="margin-top:10px"></div>');
  $.ajax({
    type: 'GET',
    url: 'sharerlink.php?display=1',
    success: function (html) {
      $("#sharerlist").html(html);
    }
  });
}
function initSharerlink() {
  $("#sharerlist").html('<div class="loader" style="margin-top:10px"></div>');
  $.ajax({
    type: 'GET',
    url: 'sharerlink.php?display=1',
    success: function (html) {
      $("#sharerlist").html(html);
      checkSLState();
    }
  });
}
function checkSLState() {
  if (clearTimer == true) {
    clearTimeout(refreshSharerlink);
    clearTimer = false;
  }
  $.ajax({
    type: "GET",
    url: "subfiles/sharerlink_check.php?state=1&i=" + Math.random(),
    success: function (data) {
      if (prevStateCycle == false) {
        prevState = data;
        prevStateCycle = true;
        refreshSharerlink = setTimeout("checkSLState()", 300000);
        clearTimer = true;
      } else {
        if (data !== prevState) {
          initSharerlink();
          prevState = data;
          refreshSharerlink = setTimeout("checkSLState()", 300000);
          clearTimer = true;
        } else {
          refreshSharerlink = setTimeout("checkSLState()", 300000);
          clearTimer = true;
        }
      }
    }
  });
}
</script>