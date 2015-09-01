<?php
   
if (!defined('FORUM_ROOT')) {	define('FORUM_ROOT', '../forum/'); }
if (!defined('SITE_ROOT')) {	define('SITE_ROOT', './'); }
    
require_once(SITE_ROOT.'portal_config.php');
require_once(SITE_ROOT.'include/functions.php');
require_once(SITE_ROOT.'include/database.class.php');

if (isset($_GET['display']) && $_GET['display'] == 1) { populate_requestbox(); }
else { header('Location: '.SITE_ROOT.'404.php'); }

function populate_requestbox() {   
    
    $db = new SQL(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, false);
    $db->query("SELECT COUNT(id) FROM ip_requests"); 
    $total_request = implode($db->fetch_assoc());
    $db->query("SELECT COUNT(id) FROM ip_reply"); 
    $total_reply = implode($db->fetch_assoc());
    $db->close();
    
    echo '<div class="alert alert-info">This is <strong>User\'s Request</strong> section (currently contained <strong>'.$total_request.'</strong> request shouts and <strong>'.$total_reply.'</strong> replies). Just use <code>!request</code> code in your shout to make them appear here. Please note that not all your requests will be replied. Lucky if you have!</div>';
    
    echo '<div id="containerx">';
    echo '<div class="data"></div>';
    echo '<div class="pagination"></div>';
    echo '</div>';
    
    echo '
    <script>
    var replyID;
    
    $(document).ready(function () { // START DOCUMENT.READY
    
    $(".tip-top").tooltip();
    
    function loadData(page){
      $("#containerx").html("<div class=\"loader\" style=\"margin-top:10px\"></div>").fadeIn("fast");
      $.ajax({
        type: "GET",
        url: "subfiles/requestbox_more.php?page="+page,
        success: function(msg){
          $("#containerx").html(msg);
        }
      });
    }
    loadData(1);  // For first time page load default results
    $("#containerx .pagination li.enx").live("click",function(e){
      e.preventDefault();
      var page = $(this).attr("p");
      loadData(page);
    });
    
    }); // END DCOUMENT.READY
    
    </script>
    ';
}
?>