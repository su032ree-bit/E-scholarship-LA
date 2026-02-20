<?php

function urlweb($connect1){
	$sql = mysqli_query($connect1,"select y_url from tb_year ");
	// return @mysql_result($sql,0,'y_url');
	return mysqli_fetch_assoc($sql)['y_url'];
}

function name_st($type,$connect1){
	$sql = mysqli_query($connect1,"select * from tb_year limit 0,1");
	$re = mysqli_fetch_array($sql);
	if($type=='1'){
		return $re['st_name_1'];
	}else if($type=='2'){
		return $re['st_name_2'];
	}else if($type=='3'){
		return $re['st_name_3'];
	}
}

function committee($id,$connect1){
	$id = (int)$id;
	$sql = mysqli_query($connect1,"select tc_name from tb_teacher where tc_id = '$id' ");
	// return @mysql_result($sql,0,'tc_name');
	return mysqli_fetch_assoc($sql)['tc_name'];
}

function committee_score($id,$idcom,$connect1){
	$id = (int)$id;
	$idcom = (int)$idcom;
	$sql = mysqli_query($connect1,"select * from tb_scores where st_id = '$id' and tc_id = '$idcom' ");
	return @mysqli_num_rows($sql);
}

function teacher($id,$connect1){
	$id = (int)$id;
	$sql = mysqli_query($connect1,"select tc_name from tb_teacher where tc_id = '$id' ");
	// return @mysql_result($sql,0,'tc_name');
	return mysqli_fetch_assoc($sql)['tc_name'];
}

function program($id,$connect1){
	$id = (int)$id;
	$sql = mysqli_query($connect1,"select g_program from tb_program where g_id = '$id' ");
	// return @mysql_result($sql,0,'g_program');
	return mysqli_fetch_assoc($sql)['g_program'];

}

function year1($connect1){
	$sql = mysqli_query($connect1,"select y_year from tb_year limit 0,1");
	// return @mysql_result($sql,0,'y_year');
	return mysqli_fetch_assoc($sql)['y_year'];
}

function sex($sex){
	if($sex==1)
		return 'นาย';
	else if($sex==2)
		return 'นางสาว';
}

function datedate($datedate){
	list($y,$m,$d)=explode("-",$datedate);
	$new_year = $y+543;
	$month = array('01'=>'มกราคม','02'=>'กุมภาพันธ์','03'=>'มีนาคม','04'=>'เมษายน','05'=>'พฤษภาคม','06'=>'มิถุนายน','07'=>'กรกฎาคม','08'=>'สิงหาคม','09'=>'กันยายน','10'=>'ตุลาคม','11'=>'พฤศจิกายน','12'=>'ธันวาคม');
	return (int)$d.' '.$month[$m].' '.$new_year;
}

function datetime($datedate){
	list($date,$time)=explode(" ",$datedate);
	list($y,$m,$d)=explode("-",$date);
	list($h,$i,$s)=explode(":",$time);
	$new_year = $y+543;
	$month = array('01'=>'มกราคม','02'=>'กุมภาพันธ์','03'=>'มีนาคม','04'=>'เมษายน','05'=>'พฤษภาคม','06'=>'มิถุนายน','07'=>'กรกฎาคม','08'=>'สิงหาคม','09'=>'กันยายน','10'=>'ตุลาคม','11'=>'พฤศจิกายน','12'=>'ธันวาคม');
	return (int)$d.' '.$month[$m].' '.$new_year.' เวลา '.$h.':'.$i.' น.';
}

function birthday($dmy,$birthday){
	list($yy,$mm,$dd)=explode("-",$birthday);
	if($dmy==1){
		return $dd;
	}else if($dmy==2){
		return $mm;
	}else if($dmy==3){
		return $yy;
	} 
}


//หาจำนวนวันที่ห่างกัน ว่ากี่วัน
function dateDriff($dateStart){
	list($dateCut,$times)=explode(" ",$dateStart);
	list($y,$m,$d) = explode("-",$dateCut);
	$DateStart = $d; //วันเริ่มต้น
	$MonthStart = $m; //เดือนเริ่มต้น
	$YearStart = $y; //ปีเริ่มต้น

	$dateToday = date("Y-m-d");	
	list($yy,$mm,$dd)=explode("-",$dateToday);
	$DateEnd = $dd; //วันสิ้นสุด
	$MonthEnd = $mm; //เดือนสิ้นสุด
	$YearEnd = $yy; //ปีสิ้นสุด

	$End = mktime(0,0,0,$MonthEnd,$DateEnd,$YearEnd);
	$Start = mktime(0,0,0,$MonthStart ,$DateStart ,$YearStart);

	$DateNum=ceil(($End -$Start)/86400); // 28 เอาวันที่ ทั้งสองมาลบกัน
	
	if($DateNum<=5){
		$pic = '<img src="images/icon/new_icon.gif" border="0" >';
	}else{
		$pic = "";
	}
	return $pic;
	
	
}



function datenow(){
	$d = date("d");
	$m = date("m");
	$y = date("Y")+543;
	$h = date("H:i");
	$month = array('01'=>'มกราคม','02'=>'กุมภาพันธ์','03'=>'มีนาคม','04'=>'เมษายน','05'=>'พฤษภาคม','06'=>'มิถุนายน','07'=>'กรกฎาคม','08'=>'สิงหาคม','09'=>'กันยายน','10'=>'ตุลาคม','11'=>'พฤศจิกายน','12'=>'ธันวาคม');
	return 'วันที่ '.(int)$d.'  เดือน '.$month[$m].' พ.ศ.'.$y;
	// return 'วันที่ '.(int)$d.'  เดือน '.$month[$m].' พ.ศ.'.$y.'  ขณะนี้เวลา '.$h.' น.';
}


//หน้า admin
function page($total,$Prev_Page,$st,$end,$Page,$Num_Pages,$ad){
	$PHP_SELF = $_SERVER['PHP_SELF'];
	/* สร้าง ปุ่มย้อนกลับ */
	if($total=='0'){echo "";}else{
			if($Prev_Page)
			echo " <a  class='mynavi'href=' $PHP_SELF?Page=$Prev_Page&ad=$ad'><< </a>";
				if($Prev_Page>4){
					echo " <a  class='mynavi' href='$PHP_SELF?Page=1&ad=$ad'>1</a><span class='pgnavi'>...</span>";
				}
				
			for($i=$st; $i<=$end; $i++){
				if($i != $Page ){
					echo "<a  class='mynavi'href='$PHP_SELF?Page=$i&ad=$ad'>$i</a>";
				} else {
					echo "<b><span class='pgnavi'>&nbsp; $i &nbsp;</span></b>";
				}
			}
			
			/*สร้างปุ่มเดินหน้า */
			if($end!=$Num_Pages){
				echo "<span class='pgnavi'>...</span><a class='pgnavi' href ='$PHP_SELF?Page=$Num_Pages&ad=$ad'>$Num_Pages</a>";
				echo "<a class='mynavi' href ='$PHP_SELF?Page=$Next_Page&ad=$ad'> >> </a>";
			}
	} 

}

?>