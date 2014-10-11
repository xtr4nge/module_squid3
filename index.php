<? 
/*
	Copyright (C) 2013-2014  xtr4nge [_AT_] gmail.com

	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/ 
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>FruityWifi</title>
<script src="../js/jquery.js"></script>
<script src="../js/jquery-ui.js"></script>
<link rel="stylesheet" href="../css/jquery-ui.css" />
<link rel="stylesheet" href="../css/style.css" />
<link rel="stylesheet" href="../../../style.css" />

<script>
$(function() {
    $( "#action" ).tabs();
    $( "#result" ).tabs();
});

</script>

</head>
<body>

<? include "../menu.php"; ?>

<br>

<?

include "../../config/config.php";
include "../../login_check.php";
include "../../functions.php";
include "_info_.php";

// Checking POST & GET variables...
if ($regex == 1) {
	regex_standard($_POST["newdata"], "msg.php", $regex_extra);
    regex_standard($_GET["logfile"], "msg.php", $regex_extra);
    regex_standard($_GET["action"], "msg.php", $regex_extra);
    regex_standard($_POST["service"], "msg.php", $regex_extra);
}

$newdata = $_POST['newdata'];
$logfile = $_GET["logfile"];
$action = $_GET["action"];
$tempname = $_GET["tempname"];
$service = $_POST["service"];

// DELETE LOG
if ($logfile != "" and $action == "delete") {
    $exec = "$bin_rm ".$mod_logs_history.$logfile.".log";
    exec("$bin_danger \"$exec\"", $dump);
}

?>

<div class="rounded-top" align="left"> &nbsp; <b>squid3</b> </div>
<div class="rounded-bottom">
  <form name="ss_mode" style="margin=0px" action="includes/module_action.php" method="GET">

    &nbsp;&nbsp;version <?=$mod_version?><br>
    <? 
    if (file_exists("$bin_squid3")) { 
        echo "&nbsp;&nbsp; squid3 <font style='color:lime'>installed</font><br>";
    } else {
        echo "&nbsp;&nbsp; squid3 <a href='includes/module_action.php?install=install_squid3' style='color:red'>install</a><br>";
    } 
    ?>
    
    <?
    $isurlrewriteup = exec("/bin/grep '^url_rewrite_program' $mod_path/includes/squid.conf");
    if ($isurlrewriteup != "") {    
        echo "&nbsp;&nbsp;&nbsp;Inject  <font color=\"lime\"><b>enabled</b></font>.&nbsp; | <a href=\"includes/module_action.php?service=url_rewrite&action=stop\"><b>stop</b></a><br />";
    } else { 
        echo "&nbsp;&nbsp;&nbsp;Inject  <font color=\"red\"><b>disabled</b></font>. | <a href=\"includes/module_action.php?service=url_rewrite&action=start\"><b>start</b></a><br />";
    }
    ?>
    
    <?
    $exec = "$bin_iptables -nvL -t nat |grep -E 'REDIRECT.+3128'";
    $isiptablesup = exec("$bin_danger \"$exec\"" );
    if ($isiptablesup != "") { 
        echo "&nbsp;Iptables  <font color=\"lime\"><b>enabled</b></font>.&nbsp; | <a href=\"includes/module_action.php?service=iptables&action=stop\"><b>stop</b></a><br />";
    } else { 
        echo "&nbsp;Iptables  <font color=\"red\"><b>disabled</b></font>. | <a href=\"includes/module_action.php?service=iptables&action=start\"><b>start</b></a><br />";
    }
    ?>
    
    <?
    $ismoduleup = exec("ps auxww | grep squid3 | grep -v -e 'grep squid3'");
    if ($ismoduleup != "") {
        echo "&nbsp;&nbsp; squid3  <font color=\"lime\"><b>enabled</b></font>.&nbsp; | <a href=\"includes/module_action.php?service=squid3&action=stop&page=module\"><b>stop</b></a>";
        echo "<input type='hidden' name='action' value='stop'>";
        echo "<input type='hidden' name='page' value='module'>";
    } else { 
        echo "&nbsp;&nbsp; squid3  <font color=\"red\"><b>disabled</b></font>. | <a href=\"includes/module_action.php?service=squid3&action=start&page=module\"><b>start</b></a>"; 
        echo "<input type='hidden' name='action' value='start'>";
        echo "<input type='hidden' name='page' value='module'>";
    }
    ?>
    
    <select name="action" class="module" onchange='this.form.submit()' <? if ($ismoduleup != "") echo "disabled"?>>
        <?
        $template_path = "$mod_path/includes/templates/";
        $templates = glob($template_path.'*');
        //print_r($templates);

        for ($i = 0; $i < count($templates); $i++) {
            $filename = str_replace($template_path,"",$templates[$i]);
            if ($filename == $url_rewrite_program) echo "<option selected>"; else echo "<option>\n"; 
            echo "$filename";
            echo "</option>\n";
        }
        ?>
    </select> 
    
    <input type="hidden" name="change_js" value="1">
  </form>
</div>

<br>


<div id="msg" style="font-size:largest;">
Loading, please wait...
</div>

<div id="body" style="display:none;">


    <div id="result" class="module">
        <ul>
            <li><a href="#result-1">Output</a></li>
            <li><a href="#result-2">Lists</a></li>
            <li><a href="#result-3">History</a></li>
			<li><a href="#result-4">About</a></li>
        </ul>
        <div id="result-1">
            <form id="formLogs-Refresh" name="formLogs-Refresh" method="POST" autocomplete="off" action="index.php">
            <input type="submit" value="refresh">
            <br><br>
            <?
                if ($logfile != "" and $action == "view") {
                    $filename = $mod_logs_history.$logfile.".log";
                } else {
                    $filename = $mod_logs;
                }
            
                $data = open_file($filename);
                
                // REVERSE
                //$data_array = explode("\n", $data);
                //$data = implode("\n",array_reverse($data_array));
                
            ?>
            <textarea id="output" class="module-content" style="font-family: courier;"><?=htmlspecialchars($data)?></textarea>
            <input type="hidden" name="type" value="logs">
            </form>
            
        </div>

        <!-- START LISTS -->
        
        <div id="result-2" >
            <form id="formTemplates" name="formTemplates" method="POST" autocomplete="off" action="includes/save.php">
            <input type="submit" value="save">       
            
            <br><br>
            <?
                if ($tempname != "") {
                    $filename = "$mod_path/includes/templates/".$tempname;
                    
                    $data = open_file($filename);
                
                } else {
                    $data = "";
                }
                
            ?>

            <textarea id="inject" name="newdata" type="text" class="module-content" style="font-family: courier;"><?=htmlspecialchars($data)?></textarea>
            <input type="hidden" name="type" value="templates">
            <input type="hidden" name="action" value="save">
            <input type="hidden" name="tempname" value="<?=$tempname?>">
            </form>
            
        <br>
            
        <table border=0 cellspacing=0 cellpadding=0>
            <tr>
            <td class="general">
                Template
            </td>
            <td>
            <form id="formTempname" name="formTempname" method="POST" autocomplete="off" action="includes/save.php">
                <select name="tempname" onchange='this.form.submit()'>
                <option value="0">-</option>
                <?
                $template_path = "$mod_path/includes/templates/";
                $templates = glob($template_path.'*');
                //print_r($templates);

                for ($i = 0; $i < count($templates); $i++) {
                    $filename = str_replace($template_path,"",$templates[$i]);
                    if ($filename == $tempname) echo "<option selected>"; else echo "<option>"; 
                    echo "$filename";
                    echo "</option>";
                }
                ?>
                </select>
                <input type="hidden" name="type" value="templates">
                <input type="hidden" name="action" value="select">
            </form>
            </td>
            <tr>
            <td class="general" style="padding-right:10px">
                Add/Rename
            </td>
            <td>
            <form id="formTempname" name="formTempname" method="POST" autocomplete="off" action="includes/save.php">
                <select name="new_rename">
                <option value="0">- add template -</option>
                <?
                $template_path = "$mod_path/includes/templates/";
                $templates = glob($template_path.'*');
                //print_r($templates);

                for ($i = 0; $i < count($templates); $i++) {
                    $filename = str_replace($template_path,"",$templates[$i]);
                    echo "<option>"; 
                    //if ($filename == $tempname) echo "<option selected>"; else echo "<option>";
                    echo "$filename";
                    echo "</option>";
                }
                ?>
                
                </select>
                <input class="ui-widget" type="text" name="new_rename_file" value="" style="width:150px">
                <input type="submit" value="add/rename">
                
                <input type="hidden" name="type" value="templates">
                <input type="hidden" name="action" value="add_rename">
                
            </form>
            </td>
            </tr>
            
            <tr><td><br></td></tr>
            
            <tr>
            <td>
                
            </td>
            <td>
            <form id="formTempDelete" name="formTempDelete" method="POST" autocomplete="off" action="includes/save.php">
                <select name="new_rename">
                <option value="0">-</option>
                <?
                $template_path = "$mod_path/includes/templates/";
                $templates = glob($template_path.'*');
                //print_r($templates);

                for ($i = 0; $i < count($templates); $i++) {
                    //$filename = $templates[$i];
                    $filename = str_replace($template_path,"",$templates[$i]);
                    echo "<option>"; 
                    echo "$filename";
                    echo "</option>";
                }
                ?>
                
                </select>

                <input type="submit" value="delete">
                
                <input type="hidden" name="type" value="templates">
                <input type="hidden" name="action" value="delete">
                
            </form>
            </td>
            </tr>
        </table>
        </div>

        <!-- END LISTS -->

        <div id="result-3">
            <input type="submit" value="refresh">
            <br><br>
            
            <?
            $logs = glob($mod_logs_history.'*.log');
            print_r($a);

            for ($i = 0; $i < count($logs); $i++) {
                $filename = str_replace(".log","",str_replace($mod_logs_history,"",$logs[$i]));
                echo "<a href='?logfile=".str_replace(".log","",str_replace($mod_logs_history,"",$logs[$i]))."&action=delete&tab=2'><b>x</b></a> ";
                echo $filename . " | ";
                echo "<a href='?logfile=".str_replace(".log","",str_replace($mod_logs_history,"",$logs[$i]))."&action=view'><b>view</b></a>";
                echo "<br>";
            }
            ?>
            
        </div>
		
		<!-- END HISTORY -->
		
		<!-- ABOUT -->

        <div id="result-4" class="history">
			<? include "includes/about.php"; ?>
		</div>
		
		<!-- END ABOUT -->
        
    </div>

    <?
    if ($_GET["tab"] == 1) {
        echo "<script>";
        echo "$( '#result' ).tabs({ active: 1 });";
        echo "</script>";
    } else if ($_GET["tab"] == 2) {
        echo "<script>";
        echo "$( '#result' ).tabs({ active: 2 });";
        echo "</script>";
    } else if ($_GET["tab"] == 3) {
        echo "<script>";
        echo "$( '#result' ).tabs({ active: 3 });";
        echo "</script>";
    } else if ($_GET["tab"] == 4) {
        echo "<script>";
        echo "$( '#result' ).tabs({ active: 4 });";
        echo "</script>";
    } 
    ?>

</div>

<script type="text/javascript">
$(document).ready(function() {
    $('#body').show();
    $('#msg').hide();
});
</script>

</body>
</html>
