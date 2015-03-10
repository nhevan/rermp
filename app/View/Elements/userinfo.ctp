<?
$users = $this->requestAction(
	"users/getUserInfo/$id"
);
if($users!=null) echo $users['User'][$what];
else {
	$users = "Creatd by root.";
	echo $users;
}

?>