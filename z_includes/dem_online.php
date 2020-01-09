<?php
$timexpired = 600;
 $conn;
$timeout = time() - $timexpired;
mysqli_query("DELETE FROM `tgp_online` WHERE `time` < ".$timeout."", $this->conn);
mysqli_query("OPTIMIZE TABLE `tgp_online`", $this->conn);

if (empty($HTTP_X_FORWARDED_FOR)) $IP_NUMBER = getenv("REMOTE_ADDR");
else $IP_NUMBER = $HTTP_X_FORWARDED_FOR;
$url	=	getenv("QUERY_STRING");

$result = mysqli_query("SELECT * FROM tgp_online WHERE ip='$IP_NUMBER' and user=".$THANHVIEN["id"], $this->conn);
$num_rows = mysqli_num_rows($result);
if($num_rows != 0) mysqli_query("UPDATE tgp_online SET time='".time()."', site='".$url."' WHERE `ip`='$IP_NUMBER' and user=".$THANHVIEN["id"], $this->conn);
else
{
	$sql	=	"INSERT INTO tgp_online VALUES ('$IP_NUMBER','".time()."','".$url."','".getenv("HTTP_USER_AGENT")."',".$THANHVIEN["id"].")";
	mysqli_query($sql, $this->conn);
	
		// Bat dau dem theo ngay 
		$result = mysqli_query("SELECT * FROM tgp_online_daily WHERE ngay='".lg_date::vn_other(time(),"d/m/Y")."'", $this->conn);
		$num_rows = mysqli_num_rows($result);
		if($num_rows != 0) mysqli_query("UPDATE tgp_online_daily SET bo_dem = bo_dem+1 WHERE `ngay`='".lg_date::vn_other(time(),"d/m/Y")."'", $this->conn);
		else mysqli_query("INSERT INTO tgp_online_daily VALUES ('".lg_date::vn_other(time(),"d/m/Y")."',1)", $this->conn);
		// Ket thuc
}
// Ket thuc
?>