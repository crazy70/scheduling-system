<?php session_start(); ?>
<?php
if(isset($_POST['commit']))
{
	$sub = $_POST['sub'];
	$scroll = $_POST['scroll'];
include '../php_script/connectDB.php';
$sql="SELECT COUNT(*) as cnt FROM sub WHERE sub.coursebookingid= '".$scroll."'";
$result = mysqli_query($conn,$sql);
$row = mysqli_fetch_array($result); 
$cnt = $row['cnt'];
if($cnt < 1)
{
	$sql="INSERT INTO sub(coursebookingid,count) VALUES('".$scroll."',1)";
}
else
{
	$sql="UPDATE sub 
    SET count = count + 1
    WHERE coursebookingid = '".$scroll."'";
}
$result = mysqli_query($conn,$sql);
}
if(isset($_GET['submit']))
{
	
	$_SESSION["startdate"] = $_GET["startdate"];
	if($_GET["campusid"]=="Select a campus")
	{$_GET["campusid"] = "Any";
}
	$_SESSION["campusid"] = $_GET["campusid"];
	if(isset($_GET["mon"]))
	{
		$_SESSION["mon"]  = $_GET["mon"];
	}
	else
	{
		$_SESSION["mon"]  = 0;
	}
	if(isset($_GET["tue"]))
	{
		$_SESSION["tue"]  = $_GET["tue"];
	}
	else
	{
		$_SESSION["tue"]  = 0;
	}
	if(isset($_GET["wed"]))
	{
		$_SESSION["wed"]  = $_GET["wed"];
	}
	else
	{
		$_SESSION["wed"]  = 0;
	}
	if(isset($_GET["thu"]))
	{
		$_SESSION["thu"]  = $_GET["thu"];
	}
	else
	{
		$_SESSION["thu"]  = 0;
	}
	if(isset($_GET["fri"]))
	{
		$_SESSION["fri"]  = $_GET["fri"];
	}
	else
	{
		$_SESSION["fri"]  = 0;
	}
	if(isset($_GET["sat"]))
	{
		$_SESSION["sat"]  = $_GET["sat"];
	}
	else
	{
		$_SESSION["sat"]  = 0;
	}
}

	
	$campusid = $_SESSION["campusid"];
	if(isset($_GET['change']))
	{
		$_SESSION["startdate"] = $_GET['change'];
		$_SESSION["moreDays"]=0;
	}
	$moreDays = 12;
	if(isset($_GET['more']))
	{
		$_SESSION["moreDays"] += 14;
		$moreDays +=$_SESSION["moreDays"];
		unset($_GET['more']);
		
	}
	
	$startdate = $_SESSION["startdate"];
	$_SESSION["enddate"] = date('Y-m-d', strtotime($startdate. ' + '.$moreDays.' days'));
	$enddate = $_SESSION["enddate"];
	
	$mon = $_SESSION["mon"];
	$tue = $_SESSION["tue"];
	$wed = $_SESSION["wed"];
	$thu = $_SESSION["thu"];
	$fri = $_SESSION["fri"];
	$sat = $_SESSION["sat"];
	$days = array($mon,$tue,$wed,$thu,$fri,$sat);
	$day = array("Mon","Tue","Wed","Thu","Fri","Sat");
	$count=0;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
	<?php $title="view recurring"; ?>
	<?php include '../php_includes/head_elements.php'; ?>
	<?php include '../php_includes/alertbox.php'; ?>
	<link href="../css/test.css" rel="stylesheet">
	
</head>
<body>
<div id="page-container">

			<?php include '../php_includes/header.php'; ?>
			<?php include '../php_includes/nav.php'; ?>
			<div class="col-6 col-m-9 content">
				<h1>view Booking</h1>	
			<div id="date">
			<h2>
<?php
$td = preg_split("/\-/",$startdate);
$dd = preg_split("/\-/",$enddate);
			echo $td[2]."-".$td[1]."-".$td[0]." ~ ".$dd[2]."-".$dd[1]."-".$dd[0];
?>
</h2>
</div>
<h2 id="cohort"></h2>
<br />
<div class="form1">

<label for='startdate'>Start Week:</label>
<input type="date" id="startdate" name="startdate" value="" onchange="loadNew()" /><br />
<button type="button" id="next" onclick="loadMore()" >Load two weeks more</button>
</div>

<div id='error'>
		<?php
				if(isset($_SESSION['error']))
				{	
					print $_SESSION['error'];	
					unset($_SESSION['error']);
				}
				
		?>
</div><!--error--><br />

<div class="form1">
<?php
if(isset($_GET['campusid']))
{
	$_SESSION['campusid']=$_GET['campusid'];
	unset($_SESSION['buildingid']);
}
if(isset($_GET['buildingid']))
{
	$_SESSION['buildingid']=$_GET['buildingid'];
}
include '../php_script/connectDB.php';
$sql="SELECT * FROM campus";
$result = mysqli_query($conn,$sql);
echo "<label for='campusid'>Campus: </label>
<select id='campusid' name='campusid' onchange='search(this.value,0)'>";
echo "<option>Any</option>";
while($row = mysqli_fetch_array($result)) {
	$campusid = $row['campusid'];
	
	
	echo "<option value='".$campusid."'"; if(isset($_SESSION['campusid'])) {if($_SESSION['campusid']==$campusid) {echo " selected";}} echo ">" .$row['campusname']."</option>";
}
echo "</select>";
mysqli_close($conn);
?>

<?php
include '../php_script/connectDB.php';
$sql="SELECT * FROM building";
$result = mysqli_query($conn,$sql);
echo "<label for='buildingid'>Building: </label>
<select name='buildingid' id='buildingid' onchange='search(this.value,1)'>";
echo "<option>Select a building</option>";
while($row = mysqli_fetch_array($result)) {
	$buildingid = $row['buildingid'];
	
	echo "<option value='".$buildingid."'"; if(isset($_SESSION['buildingid'])) {if($_SESSION['buildingid']==$buildingid) {echo " selected";}} echo ">" .$row['buildingname']."</option>";
}
echo "</select>";
mysqli_close($conn);
?>

<label>Capacity (More than)</label>
<input type="text" id="capacity" onchange="filterCapacity(this.value)" /><br />
<div class="messagepop pop">
    <form method="post" id="new_message" action="./admin_view_recurring_view.php">
        <p><label for="sub">Sub</label><input type="checkbox" name="sub" id="sub"/></p>
		<p><input type="text" value="" name="scroll" id="scroll"/></p>
        <p><input type="submit" value="Confirm" name="commit" id="message_submit"/> or <a class="close" href="/">Cancel</a></p>
    </form>
</div>
</div><!-- end of form1 -->
 
<div id="time_table" class = "border"><!-- start of time table -->


<script src="../js/jquery.js"></script>
<script>
<?php
if(isset($_GET['campusid']))
{
	$jsonid = json_encode($_GET['campusid']);
	echo "var campusid = ".$jsonid.";\n";
}
else
{
	echo "var campusid=null;\n";
}
?>
var form_allowed_to_submit = false;
function changeOptions(campusid) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
	
        xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById("buildingid").innerHTML = xmlhttp.responseText;
            }
        }
		
        xmlhttp.open("GET","../php_script/getbuildingform_script.php?campusid="+campusid,true);
        xmlhttp.send();
    } 
	if(campusid!=null)
	{

		changeOptions(campusid);
	}
	function filterCapacity(value) {
		
		window.location.href = "./admin_view_recurring_view.php?capacity=" + value;
	}
	function search(searchValue,searchKey) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
			if(searchKey==0)
			{
				window.location.href = "./admin_view_recurring_view.php?campusid=" + searchValue;
				 
			}
			if(searchKey==1)
			{
				window.location.href = "./admin_view_recurring_view.php?buildingid=" + searchValue; 
			}
			
    } 
	
	</script>
<?php
	include '../php_script/connectDB.php';
		$count=0;
		$rooms = array();
		$days = array();
		if(isset($_SESSION['campusid']) && ($_SESSION['campusid']!="Any"))
		{
			$campusid=$_SESSION['campusid'];
			if(isset($_SESSION['buildingid']))
			{
				$buildingid = $_SESSION['buildingid'];
			if(isset($_GET['capacity']))
			{
				$capacity = $_GET['capacity'];
				$result2 = "SELECT room.roomname,room.roomid,b.buildingname,campus.*,room.roomtype FROM building b, room,campus WHERE b.buildingid=room.buildingid AND b.campusid=campus.campusid AND b.campusid='".$campusid."' AND b.buildingid='".$buildingid."' AND room.capacity>'$capacity'";
			}
			else
			{
			
			$result2 = "SELECT room.roomname,room.roomid,b.buildingname,campus.*,room.roomtype FROM building b, room,campus WHERE b.buildingid=room.buildingid AND b.campusid=campus.campusid AND b.campusid='".$campusid."' AND b.buildingid='".$buildingid."'";
			}
			}
			else
			{
				$result2 = "SELECT room.roomname,room.roomid,b.buildingname,campus.*,room.roomtype FROM building b, room,campus WHERE b.buildingid=room.buildingid AND b.campusid=campus.campusid AND b.campusid='".$campusid."'";
			}
		}
		elseif(isset($_GET['buildingid']) && ($_GET['buildingid']!="Select a building"))
		{
			$buildingid=$_GET['buildingid'];
			$result2 = "SELECT room.roomname,room.roomid,,room.roomtype,b.buildingname FROM building b, room WHERE b.buildingid=room.buildingid AND b.buildingid='".$buildingid."'";
			
		}
		elseif(isset($_GET['capacity']))
		{
			$capacity = $_GET['capacity'];
			$result2 = "SELECT room.roomname,room.roomid,room.roomtype FROM room WHERE room.capacity>'$capacity'";
		}
		else
		{
			$result2 = "SELECT room.roomname,room.roomid,room.roomtype FROM room";
		}
		if(($runquery2 = $conn->query($result2)) )
		{
			while($row = $runquery2->fetch_assoc())
			{
				
				if($row["roomtype"]==1)
				{
					$childQuery = "SELECT m.roomname,m.multiroomchildid FROM multiroomchild m WHERE m.roomid='".$row["roomid"]."'";
					if(($childrunquery = $conn->query($childQuery)) )
					{
					while($row2 = $childrunquery->fetch_assoc())
					{
						$rooms[$row2["multiroomchildid"]]["name"] = $row2["roomname"];
						$rooms[$row2["multiroomchildid"]]["id"] = $row2["multiroomchildid"];
						$rooms[$row2["multiroomchildid"]]["type"] = $row["roomtype"];
					}
					}
					if(!$childrunquery)
					{
						 $_SESSION['error'] = "Query error: ".mysqli_error($conn);
					}
				}
				else
				{
				$rooms[$row["roomid"]]["name"] = $row["roomname"];
				$rooms[$row["roomid"]]["id"] = $row["roomid"];
				}
			}
		}
		if(!$runquery2)
		{
			  $_SESSION['error'] = "Query error: ".mysqli_error($conn);
		}
$result = "SELECT c.*,t.* FROM coursebooking c,tutor t WHERE c.startdate >='$startdate' AND c.startdate <='$enddate' AND c.tutorid=t.tutorid";	
if(($runquery = $conn->query($result)) )
		{
			while($row = $runquery->fetch_assoc())
			{
				if(isset($rooms[$row["roomid"]]["name"]))
				{
				$rooms[$row["roomid"]]["booked"][$count]["roomid"] = $row["roomid"];
				$rooms[$row["roomid"]]["booked"][$count]["classstarttime"] = $row["classstarttime"];
				$rooms[$row["roomid"]]["booked"][$count]["classendtime"] = $row["classendtime"];
				$rooms[$row["roomid"]]["booked"][$count]["coursebookingid"] = $row["coursebookingid"];
				$rooms[$row["roomid"]]["booked"][$count]["startdate"] = $row["startdate"];
				$rooms[$row["roomid"]]["booked"][$count]["enddate"] = $row["enddate"];
				$rooms[$row["roomid"]]["booked"][$count]["coursename"] = $row["coursename"];
				$rooms[$row["roomid"]]["booked"][$count]["tutor"] = $row["firstname"]." ".$row["lastname"];
				$rooms[$row["roomid"]]["booked"][$count]["days"]["Mon"] = $row["dayMon"];
				$rooms[$row["roomid"]]["booked"][$count]["days"]["Tue"] = $row["dayTue"];
				$rooms[$row["roomid"]]["booked"][$count]["days"]["Wed"] = $row["dayWed"];
				$rooms[$row["roomid"]]["booked"][$count]["days"]["Thu"] = $row["dayThu"];
				$rooms[$row["roomid"]]["booked"][$count]["days"]["Fri"] = $row["dayFri"];
				$rooms[$row["roomid"]]["booked"][$count]["days"]["Sat"] = $row["daySat"];
				$count++;
				}
			}
		}
		if(!$runquery)
		{
			  $_SESSION['error'] = "Query error: ".mysqli_error($conn);
		}
		

		
$result = "SELECT c.*,t.* FROM coursebooking c,tutor t WHERE c.enddate >='$startdate' AND c.enddate <='$enddate' AND c.tutorid=t.tutorid";	
if(($runquery = $conn->query($result)) )
		{
			while($row = $runquery->fetch_assoc())
			{
				if(isset($rooms[$row["roomid"]]["name"]))
				{
				$rooms[$row["roomid"]]["booked"][$count]["roomid"] = $row["roomid"];
				$rooms[$row["roomid"]]["booked"][$count]["classstarttime"] = $row["classstarttime"];
				$rooms[$row["roomid"]]["booked"][$count]["classendtime"] = $row["classendtime"];
				$rooms[$row["roomid"]]["booked"][$count]["coursebookingid"] = $row["coursebookingid"];
				$rooms[$row["roomid"]]["booked"][$count]["startdate"] = $row["startdate"];
				$rooms[$row["roomid"]]["booked"][$count]["enddate"] = $row["enddate"];
				$rooms[$row["roomid"]]["booked"][$count]["coursename"] = $row["coursename"];
				$rooms[$row["roomid"]]["booked"][$count]["tutor"] = $row["firstname"]." ".$row["lastname"];
				$rooms[$row["roomid"]]["booked"][$count]["days"]["Mon"] = $row["dayMon"];
				$rooms[$row["roomid"]]["booked"][$count]["days"]["Tue"] = $row["dayTue"];
				$rooms[$row["roomid"]]["booked"][$count]["days"]["Wed"] = $row["dayWed"];
				$rooms[$row["roomid"]]["booked"][$count]["days"]["Thu"] = $row["dayThu"];
				$rooms[$row["roomid"]]["booked"][$count]["days"]["Fri"] = $row["dayFri"];
				$rooms[$row["roomid"]]["booked"][$count]["days"]["Sat"] = $row["daySat"];
				$count++;
				}
			}
		}
		if(!$runquery)
		{
			  $_SESSION['error'] = "Query error: ".mysqli_error($conn);
		}
		
		$result = "SELECT c.*,t.* FROM coursebooking c,tutor t WHERE c.startdate <='$startdate' AND c.enddate >= '$enddate' AND c.tutorid=t.tutorid";	
if(($runquery = $conn->query($result)) )
		{
			while($row = $runquery->fetch_assoc())
			{
				if(isset($rooms[$row["roomid"]]["name"]))
				{
				$rooms[$row["roomid"]]["booked"][$count]["roomid"] = $row["roomid"];
				$rooms[$row["roomid"]]["booked"][$count]["classstarttime"] = $row["classstarttime"];
				$rooms[$row["roomid"]]["booked"][$count]["classendtime"] = $row["classendtime"];
				$rooms[$row["roomid"]]["booked"][$count]["coursebookingid"] = $row["coursebookingid"];
				$rooms[$row["roomid"]]["booked"][$count]["startdate"] = $row["startdate"];
				$rooms[$row["roomid"]]["booked"][$count]["enddate"] = $row["enddate"];
				$rooms[$row["roomid"]]["booked"][$count]["coursename"] = $row["coursename"];
				$rooms[$row["roomid"]]["booked"][$count]["tutor"] = $row["firstname"]." ".$row["lastname"];
				$rooms[$row["roomid"]]["booked"][$count]["days"]["Mon"] = $row["dayMon"];
				$rooms[$row["roomid"]]["booked"][$count]["days"]["Tue"] = $row["dayTue"];
				$rooms[$row["roomid"]]["booked"][$count]["days"]["Wed"] = $row["dayWed"];
				$rooms[$row["roomid"]]["booked"][$count]["days"]["Thu"] = $row["dayThu"];
				$rooms[$row["roomid"]]["booked"][$count]["days"]["Fri"] = $row["dayFri"];
				$rooms[$row["roomid"]]["booked"][$count]["days"]["Sat"] = $row["daySat"];
				$count++;
				}
			}
		}
		if(!$runquery)
		{
			  $_SESSION['error'] = "Query error: ".mysqli_error($conn);
		}
		$adhoc = "SELECT c.*,b.* FROM bookinginfo c,booking b WHERE b.bookingid = c.bookingid AND b.date <='$enddate' AND b.date >= '$startdate' AND b.bookingstatus != 'cancelled'";	
		if(($runquery = $conn->query($adhoc)) )
		{
			while($row = $runquery->fetch_assoc())
			{
				if(isset($rooms[$row["roomid"]]["name"]))
				{
					$rooms[$row["roomid"]]["booked"][$count]["roomid"] = $row["roomid"];
				$rooms[$row["roomid"]]["booked"][$count]["classstarttime"] = $row["time"];
				$time = strtotime($row["time"]);
				$time = date("H:i", strtotime('+30 minutes',$time));
				$rooms[$row["roomid"]]["booked"][$count]["classendtime"] = $time;
				$date = $row["date"];
				$rooms[$row["roomid"]]["booked"][$count]["startdate"] = $date;
				$rooms[$row["roomid"]]["booked"][$count]["enddate"] = $date;
				$rooms[$row["roomid"]]["booked"][$count]["coursename"] = "Reason: ".$row["reason"]." (".$row["bookingstatus"].")";
				if($row["bookingstatus"]=="pending")
				{
					$rooms[$row["roomid"]]["booked"][$count]["pending"]="w";
				}
				$date = date('w', strtotime($date));
				
				if($date == 1)
				{
					$rooms[$row["roomid"]]["booked"][$count]["days"]["Mon"] = 1;
					}
					else
					{
					$rooms[$row["roomid"]]["booked"][$count]["days"]["Mon"] = 0;
					}
					if($date == 2)
				{
					$rooms[$row["roomid"]]["booked"][$count]["days"]["Tue"] = 1;
					}
					else
					{
					$rooms[$row["roomid"]]["booked"][$count]["days"]["Thu"] = 0;
					}
					if($date == 3)
				{
					$rooms[$row["roomid"]]["booked"][$count]["days"]["Wed"] = 1;
					}
					else
					{
					$rooms[$row["roomid"]]["booked"][$count]["days"]["Wed"] = 0;
					}
					if($date == 4)
				{
					$rooms[$row["roomid"]]["booked"][$count]["days"]["Thu"] = 1;
					}
					else
					{
					$rooms[$row["roomid"]]["booked"][$count]["days"]["Thu"] = 0;
					}
					if($date == 5)
				{
					$rooms[$row["roomid"]]["booked"][$count]["days"]["Fri"] = 1;
					}
					else
					{
					$rooms[$row["roomid"]]["booked"][$count]["days"]["Fri"] = 0;
					}
					if($date == 6)
				{
					$rooms[$row["roomid"]]["booked"][$count]["days"]["Sat"] = 1;
					}
					else
					{
					$rooms[$row["roomid"]]["booked"][$count]["days"]["Sat"] = 0;
					}
					
					

				}
				$count++;
				}
				}
				if(!$runquery)
		{
			  $_SESSION['error'] = "Query error: ".mysqli_error($conn);
		}
?>

<script>
  jQuery.fn.center = function () {
    this.css("position","absolute");
    this.css("top", jQuery(window).scrollTop());
	 
    return this;
  }
 
var target;
/* function deselect(e) {
  $('.pop').slideFadeToggle(function() {
    e.removeClass('selected');
  });    
}
 */
/* $(function() {
  $('.tt').on('click', function() {
    if($(this).hasClass('selected')) {
      deselect($(this));               
    } else {
      $(this).addClass('selected');
      $('.pop').slideFadeToggle();
	  $('.pop').center();
	  $('#scroll').val($(this).attr("data-supid"));
    }
    return false;
  });

  $('.close').on('click', function() {
    deselect($('.tt'));
    return false;
  });
}); */

$.fn.slideFadeToggle = function(easing, callback) {
	
  return this.animate({ opacity: 'toggle', height: 'toggle' }, 'fast', easing, callback);
};

$(function() {
    // Stick the #nav to the top of the window
    var date = $('#date');
    var navHomeY = date.offset().top;
    var isFixed = false;
    var $w = $(window);
    $w.scroll(function() {
        var scrollTop = $w.scrollTop();
        var shouldBeFixed = scrollTop > navHomeY;
        if (shouldBeFixed && !isFixed) {
            date.css({
                position: 'fixed',
                top: 0,
                left: date.offset().left,
                width: date.width()
            });
			date.css("background-color","white");
            isFixed = true;
        }
        else if (!shouldBeFixed && isFixed)
        {
            date.css({
                position: 'static'
            });
            isFixed = false;
        }
    });
});

<?php
$rooms = json_encode($rooms);
echo "var rooms = ".$rooms.";\n";
$startdate = json_encode($startdate);
echo "var startdate = ".$startdate.";\n";
$enddate = json_encode($enddate);
echo "var enddate = ".$enddate.";\n";
$mon = json_encode($mon);
echo "var mon = ".$mon.";\n";
$tue = json_encode($tue);
echo "var tue = ".$tue.";\n";
$wed = json_encode($wed);
echo "var wed = ".$wed.";\n";
$thu = json_encode($thu);
echo "var thu = ".$thu.";\n";
$fri = json_encode($fri);
echo "var fri = ".$fri.";\n";
$sat = json_encode($sat);
echo "var sat = ".$sat.";\n";
$days = json_encode($days);
echo "var days = ".$days.";\n";
$count = json_encode($count);
echo "var count = ".$count.";\n";
?>
var dayname = ["Mon","Tue","Wed","Thu","Fri","Sat"];


var result;
var flag=0;
var date1 = new Date(startdate);
var date2 = new Date(enddate);
var timeDiff = Math.abs(date2.getTime() - date1.getTime());
var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24)); 
var remainder = diffDays % 7;

var weeks = Math.floor(diffDays / 7);

	remainder+=1;

function createTable(numWeeks,remainder,date1,date2) {
	var thisDate;
	var thatDate;
	var body = document.getElementById("booking");
	var table = document.createElement("table");
	var tb  = document.createElement("tbody");
	var weekCnt =1;var weekCnt2 =1;
	var time = ["08:30","09:00","09:30","10:00","10:30","11:00","11:30","12:00","12:30","13:00","13:30","14:00","14:30","15:00","15:30","16:00","16:30","17:00","17:30","18:00","18:30","19:00","19:30","20:00","20:30","21:00"];
	 var time2 = ["1:00","1:30","2:00","2:30","3:00","3:30","4:00","4:30","5:00","5:30","6:00","6:30","7:00","7:30"];
	 var rid = "";
	 var timeCount = 2;
	 var tempDate;
	 var roomname ="";
	 var input = "";
	 var arr =[];
	 var cnt =0;
	 
	 var roomCnt=0;
	 var dayCnt=2;
	 var span;
	 var classname=0;
	  var rowLength =2;
	  var colLength =2;
	var weekTitle="";
	var checkWeek;
	var tempWeek;
	var array2 = [];
	var testing;
	var testing1;
	  var flag2=0;
	  var timeInput;
	  var dump;
	  var icon;
	  
		  thisDate = new Date(date1);
		  thatDate = new Date(date2);
	
		 colLength += numWeeks*dayname.length+remainder;
	 

	  for (var q in rooms) {
		  
	
	 arr[cnt] = rooms[q];
	 cnt++;
	  }
	  
	  
	  if(arr.length>0)
	  {
		 rowLength+= time.length*arr.length;
	  }
	
	 for (var i = 0; i <rowLength; i++) {
	classname = 0;
	var tr = document.createElement("tr");
	
		for (var j = 0; j < colLength; j++) {
			
		if((j==0&&i>2)&&(i%26!=2)||(i==0&&j>2)&&(j%6!=2))
		{
			
		}
		else
		{
		var td = document.createElement("td");
		if(i==0 && j==0)
			{
			icon = document.createElement("img");
				icon.src="../pic/up.png";
				icon.setAttribute("onclick","undoCollapse();");
				td.appendChild(icon);
		}
		if(!(i==0&&j==0) && !(i==1&&j==1))
		{
			if(i==0&&j==1)
			{
				td.appendChild(document.createTextNode("Weeks"));
			}
			else if(i==1&&j==0)
			{
				td.appendChild(document.createTextNode("Rooms"));
			}
			else if((i==2||i%26==2)&&j==0)
			{
				
				
			rid = arr[roomCnt]["id"];
				input = document.createElement("input");
				input.type = "checkbox";
				input.name = "roomid";
				input.setAttribute("onclick","showCheckbox("+roomCnt+")");
				input.value = rid + "!"+startdate+"!"+enddate+"!"+roomCnt+"!"+dump+"!"+mon+"!"+tue+"!"+wed+"!"+thu+"!"+fri+"!"+sat+"!";;
				
				
				td.appendChild(document.createTextNode(arr[roomCnt]["name"]));
				if(arr[roomCnt]["type"])
				{
					td.appendChild(document.createTextNode("(multiroom area)"));
				}
				roomCnt++;
				td.rowSpan="26";
			}
			else if(i==0&&(j==2||j%6==2))
			{
				weekTitle = new Date(thisDate);
				weekTitle.setDate(weekTitle.getDate()+(weekCnt-1)*7);
				
				td.appendChild(document.createTextNode("W"+weekCnt+" - "+weekTitle.toDateString()));
				td.appendChild(document.createTextNode("W"+weekCnt+" - "+weekTitle.toDateString()));
				td.colSpan="6";
				td.className = "collapse"+weekCnt;
				icon = document.createElement("img");
				icon.src="../pic/down.png";
				icon.setAttribute("onclick","clickCollapse("+weekCnt+");");
				td.appendChild(icon);
				
				
				
				weekCnt++;
			}
			else if(i==1&&j>1)
			{
				if(dayCnt%6==2)
				{
					dayCnt=2;
				}
				td.appendChild(document.createTextNode(dayname[dayCnt-2]));
				dayCnt++;
				if((j==2||j%6==2))
				{
					classname++;
				}
				td.className+="collapse"+classname;
			}
			else if(j==1&&i>1)
			{
				if(timeCount%26==2)
				{
					timeCount=2;
				}
				if(arr[roomCnt-1]["booked"])
				{
					for(var coursebooking in arr[roomCnt-1]["booked"])
							{
								if((arr[roomCnt-1]["booked"][coursebooking]["startdate"]<=startdate && arr[roomCnt-1]["booked"][coursebooking]["enddate"]>=startdate)||(arr[roomCnt-1]["booked"][coursebooking]["startdate"]<=enddate && arr[roomCnt-1]["booked"][coursebooking]["enddate"]>=enddate)||(arr[roomCnt-1]["booked"][coursebooking]["startdate"]>=startdate && arr[roomCnt-1]["booked"][coursebooking]["enddate"]<=enddate))
								{
								if((arr[roomCnt-1]["booked"][coursebooking]["days"]["Mon"]==1 && mon==1) || (arr[roomCnt-1]["booked"][coursebooking]["days"]["Tue"]==1 && tue==1) || (arr[roomCnt-1]["booked"][coursebooking]["days"]["Wed"]==1 && wed==1) || (arr[roomCnt-1]["booked"][coursebooking]["days"]["Thu"]==1 && thu==1) || (arr[roomCnt-1]["booked"][coursebooking]["days"]["Fri"]==1 && fri==1) || (arr[roomCnt-1]["booked"][coursebooking]["days"]["Sat"]==1 && sat==1))
								{
									if((arr[roomCnt-1]["booked"][coursebooking]["classstarttime"] <= time[timeCount-2] && arr[roomCnt-1]["booked"][coursebooking]["classendtime"] > time[timeCount-2]))
									{
										flag2=1;
									}
								}
								}
							}
				}
				
				td.appendChild(document.createTextNode(time[timeCount-2]));
				if(flag2==0)
				{
				timeInput = document.createElement("input");
				timeInput.setAttribute("type","checkbox");
				timeInput.className = "dis"+(roomCnt-1);
				timeInput.style="display:none";
				timeInput.value=time[timeCount-2];
				timeInput.name = "time"+(roomCnt-1)+"[]";
				}
				flag2=0;
				timeCount++;
			}
			else
			{
				if(i==2&&(j==2||j%6==2))
				{
					
				
				checkWeek = new Date(thisDate);
				checkWeek.setDate(checkWeek.getDate()+(weekCnt2-1)*7);
				weekCnt2++;
				array2[j] = checkWeek;
				
				}
				if(i>=2&&(j==2||j%6==2))
				{
					classname++;
				}

				if(arr[roomCnt-1]["booked"])
				{
					for(var col in array2)
					{
						if((col <= j)&&(j<col+6))
						{
							tempWeek = new Date(array2[col]);
						}
					}
					tempDate = new Date(tempWeek);
					tempDate.setDate(tempDate.getDate()+7);
					for(var coursebooking in arr[roomCnt-1]["booked"])
							{
								td.setAttribute("data-supid",arr[roomCnt-1]["booked"][coursebooking]["coursebookingid"]);
								testing = new Date(arr[roomCnt-1]["booked"][coursebooking]["enddate"]);
								testing1 = new Date(arr[roomCnt-1]["booked"][coursebooking]["startdate"]);
								if((testing1<=tempDate && testing1>=tempWeek)||(testing<=tempDate && testing>=tempWeek)||(testing1<=tempWeek && testing>=tempDate))
								{
									
								if(j<8)
								{
							if(arr[roomCnt-1]["booked"][coursebooking]["days"][dayname[j-2]] == 1)
							{
								if(i<28)
								{
								if(arr[roomCnt-1]["booked"][coursebooking]["classstarttime"] <= time[i-2] && arr[roomCnt-1]["booked"][coursebooking]["classendtime"] > time[i-2])
								{
									flag=1;
									cohort = arr[roomCnt-1]["booked"][coursebooking]["coursename"];
									if(arr[roomCnt-1]["booked"][coursebooking]["tutor"]==null || arr[roomCnt-1]["booked"][coursebooking]["tutor"]=="undefined")
									{
										tempStr = "";
									}
									else
									{
										tempStr = "&emsp; Tutor: "+arr[roomCnt-1]["booked"][coursebooking]["tutor"];
									}
									if(cohort=="other")
									{
									td.setAttribute("data-tooltip","Reason: "+arr[roomCnt-1]["booked"][coursebooking]["coursename"]+tempStr);
									}
									else
									{
									td.setAttribute("data-tooltip",arr[roomCnt-1]["booked"][coursebooking]["coursename"]+tempStr);
									}	
									if(arr[roomCnt-1]["booked"][coursebooking]["pending"])
									{
										td.style.backgroundColor = "yellow";
									}
									else
									{
											td.style.backgroundColor = "red";
									}
								}
								}
								else
								{
									if(arr[roomCnt-1]["booked"][coursebooking]["classstarttime"] <= time[(i-2)%26] && arr[roomCnt-1]["booked"][coursebooking]["classendtime"] > time[(i-2)%26])
								{
									flag=1;
									cohort = arr[roomCnt-1]["booked"][coursebooking]["coursename"];
									if(arr[roomCnt-1]["booked"][coursebooking]["tutor"]==null || arr[roomCnt-1]["booked"][coursebooking]["tutor"]=="undefined")
									{
										tempStr = "";
									}
									else
									{
										tempStr = "&emsp; Tutor: "+arr[roomCnt-1]["booked"][coursebooking]["tutor"];
									}
									if(cohort=="other")
									{
									td.setAttribute("data-tooltip","Reason: "+arr[roomCnt-1]["booked"][coursebooking]["coursename"]+tempStr);
									}
									else
									{
									td.setAttribute("data-tooltip",arr[roomCnt-1]["booked"][coursebooking]["coursename"]+tempStr);
									}
if(arr[roomCnt-1]["booked"][coursebooking]["pending"])
									{
										td.style.backgroundColor = "yellow";
									}
									else
									{
											td.style.backgroundColor = "red";
									}									
									}
								}
							}
								}
								else
								{
									if(arr[roomCnt-1]["booked"][coursebooking]["days"][dayname[(j-2)%6]] == 1)
							{
								if(i<28)
								{
								if(arr[roomCnt-1]["booked"][coursebooking]["classstarttime"] <= time[i-2] && arr[roomCnt-1]["booked"][coursebooking]["classendtime"] > time[i-2])
								{
									flag=1;
									cohort = arr[roomCnt-1]["booked"][coursebooking]["coursename"];
									if(arr[roomCnt-1]["booked"][coursebooking]["tutor"]==null || arr[roomCnt-1]["booked"][coursebooking]["tutor"]=="undefined")
									{
										tempStr = "";
									}
									else
									{
										tempStr = "&emsp; Tutor: "+arr[roomCnt-1]["booked"][coursebooking]["tutor"];
									}
									if(cohort=="other")
									{
									td.setAttribute("data-tooltip","Reason: "+arr[roomCnt-1]["booked"][coursebooking]["coursename"]+tempStr);
									}
									else
									{
									td.setAttribute("data-tooltip",arr[roomCnt-1]["booked"][coursebooking]["coursename"]+tempStr);
									}	
									if(arr[roomCnt-1]["booked"][coursebooking]["pending"])
									{
										td.style.backgroundColor = "yellow";
									}
									else
									{
											td.style.backgroundColor = "red";
									}
									}
								}
								else
								{
									if(arr[roomCnt-1]["booked"][coursebooking]["classstarttime"] <= time[(i-2)%26] && arr[roomCnt-1]["booked"][coursebooking]["classendtime"] > time[(i-2)%26])
								{
									flag=1;
									cohort = arr[roomCnt-1]["booked"][coursebooking]["coursename"];
									if(arr[roomCnt-1]["booked"][coursebooking]["tutor"]==null || arr[roomCnt-1]["booked"][coursebooking]["tutor"]=="undefined")
									{
										tempStr = "";
									}
									else
									{
										tempStr = "&emsp; Tutor: "+arr[roomCnt-1]["booked"][coursebooking]["tutor"];
									}
									if(cohort=="other")
									{
									td.setAttribute("data-tooltip","Reason: "+arr[roomCnt-1]["booked"][coursebooking]["coursename"]+tempStr);
									}
									else
									{
									td.setAttribute("data-tooltip",arr[roomCnt-1]["booked"][coursebooking]["coursename"]+" "+tempStr);
									}	
									if(arr[roomCnt-1]["booked"][coursebooking]["pending"])
									{
										td.style.backgroundColor = "yellow";
									}
									else
									{
											td.style.backgroundColor = "red";
									}
									}
								}
							}
								}
									
								}
							}
				}
				if(flag==1)
				{
					td.appendChild(document.createTextNode("booked"));
					td.className="tt ";
					span = document.createElement("span");
					span.className="tt-content";
					span.innerHTML=td.getAttribute("data-tooltip");
					td.appendChild(span);
					td.style.color= "green";
				}
				else
				{
				
				
				}
				td.className+="collapse"+classname;
				flag=0;
			}
		
		}
		
		
		
		tr.appendChild(td);
		
		}
		
		}
		tb.appendChild(tr);
		
	 }

	
table.appendChild(tb);
body.appendChild(table);
}

function clearTables()
{
	var booking = document.getElementById("booking");
	booking.innerHTML = "";
}


function writeHeader() {
	var header = document.getElementById("cohort");
	var str;
	str = course + " ";
	if(mon==1)
	{
		str+="Mon ";
	}
	if(tue==1)
	{
		str+="Tue ";
	}
	if(wed==1)
	{
		str+="Wed ";
	}
	if(thu==1)
	{
		str+="Thu ";
	}
	if(fri==1)
	{
		str+="Fri ";
	}
	if(sat==1)
	{
		str+="Sat ";
	}
	header.innerHTML = str;
}
function changeDay(checkboxday) {
		if(checkboxday=="Mon")
		{
			if(mon==1)
			{
				mon=0;
			}
			else
			{
				mon=1;
			}
		}
		if(checkboxday=="Tue")
		{
			if(tue==1)
			{
				tue=0;
			}
			else
			{
				tue=1;
			}
		}
		if(checkboxday=="Wed")
		{
			if(wed==1)
			{
				wed=0;
			}
			else
			{
				wed=1;
			}
		}
		if(checkboxday=="Thu")
		{
			if(thu==1)
			{
				thu=0;
			}
			else
			{
				thu=1;
			}
		}
		if(checkboxday=="Fri")
		{
			if(fri==1)
			{
				fri=0;
			}
			else
			{
				fri=1;
			}
		}
		if(checkboxday=="Sat")
		{
			if(sat==1)
			{
				sat=0;
			}
			else
			{
				sat=1;
			}
		}
		
		
		clearTables();
createTable(weeks,remainder,startdate,enddate);
	}
function showCheckbox(roomnumber) {

	$('.dis'+roomnumber).each(function(){
		if($(this).css("visibility")=="hidden")
		{
			$(this).css("visibility","visible");
		}
		else
		{
			$(this).css("visibility","hidden");
		}
	});
}
function loadNew() {
	var valu = $('#startdate').val();
	var valueDate;
	valuDate = new Date(valu);
	if(valuDate.getDay()==1)
	{
	window.location.href = './admin_view_recurring_view.php?change='+valu;
	}
	else
	{
	var d = 5000;
	alertify.set({ delay: d });
				alertify.log("startweek must start on Monday");
				}
}
function loadMore() {
	window.location.href = './admin_view_recurring_view.php?more=1';
}
function clickCollapse(className) {
	$('.collapse'+className).each(function(){
		$(this).css("display","none");
	});
}
function undoCollapse() {
	$("td[style*='display: none']").each(function(){
		$(this).css("display","table-cell");
	});
}
</script>

<div id="booking">
</div>

<script>

clearTables();
createTable(weeks,remainder,startdate,enddate);
window.history.pushState('page2', 'view booking', './admin_view_recurring_view.php');
</script>
</div><!-- end of time table -->

</div>
</div>
</body>
</html>