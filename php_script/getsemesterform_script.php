<?php
include '../php_script/connectDB.php';
$programmeid=$_GET['programmeid'];
if($programmeid=="Any")
{
	$sql="SELECT * FROM programme";
	$result = mysqli_query($conn,$sql);
}
else{
	$sql="SELECT * FROM programme WHERE programmeid='".$programmeid."'";
	$result = mysqli_query($conn,$sql);
}
$row = mysqli_fetch_array($result);
	$semesters = $row['semesters'];

echo "<input type='hidden' name='semesters' id= 'get_semesters' value='".$semesters."'>";
for($i=1;$i<=$semesters;$i++){
	echo  "<span class='tablinks' onclick='openCity(".$i.")'>".$i."</span>" ;
}
echo"<br />";
for($i=1;$i<=$semesters;$i++){
	echo  "<div id='".$i."' class='tabcontent'>
				<h3>Semester ".$i."</h3>
				<input type='date' name='semesterS".$i."' id='start_semester".$i."' readonly><br />
				<input type='number' class='summaryInputs' placeholder='Semester ".$i." duration in week' id='semester".$i."Duration' min=0 onchange='calculateSemesterEndDate(this.value,".$i.")'><br />
				<input type='date' name='semesterE".$i."' id='end_semester".$i."' readonly><br />
				<input type='number' class='summaryInputs' placeholder='break ".$i." in week' id=break".$i."' min=0 onchange='calculateSemesterStartDate(this.value,".$i.")'><br />
		 </div>" ;
}
mysqli_close($conn);
?>
