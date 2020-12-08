<?php
require_once 'config.php'; //lấy thông tin từ config
$conn = mysqli_connect($DBHOST, $DBUSER, $DBPW, $DBNAME) or die ('Không thể kết nối tới database');
$ID = $_POST['ID'];// lấy id từ chatfuel
$gioitinh = $_POST['gt']; // lấy giới tính

function request($userid,$data) { 
  global $TOKEN;
  global $BOT_ID;
  global $BLOCK_NAME;
    $url = "https://fchat.vn/api/send?user_id=$userid&block_id=$BLOCK_NAME&token=$TOKEN&$data";
  $ch = curl_init($url);
  curl_setopt($ch,CURLOPT_URL,$url);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_exec($ch);
  	if (curl_errno($ch)) {
		echo errorChat;
	} else {
		$resultStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		if ($resultStatus == 200) {
			// send ok
		} else {
			echo errorChat;
		}
	}
	curl_close($ch);
}

function sendchat($userid,$noidung){
global $JSON;
$payload = '{"'.$JSON.'"="'.$noidung.'"}';
request($userid,$payload);		
}

function isUserExist($userid) { //hàm kiểm tra xem user đã tồn tại chưa 
  global $conn;
  $result = mysqli_query($conn, "SELECT `ID` from `users` WHERE `ID` = $userid LIMIT 1");
  $row = mysqli_num_rows($result);
  return $row;
}

/// Xét giới tính
if ($gioitinh == 'male'){
$gioitinh = 1;
} else if ($gioitinh == 'female'){
$gioitinh = 2;
}

if ( !isUserExist($ID) ) { // nếu chưa tồn tại thì update lên sever
    $sql = "INSERT INTO `users` (`ID`, `trangthai`, `hangcho` ,`gioitinh`) VALUES (".$ID.", 0, 0 , $gioitinh)";
   $info = mysqli_query($conn,$sql );
}
sendchat($ID,"OK");

mysqli_close($conn);
?>