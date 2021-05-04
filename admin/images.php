
<?php

require_once('../core/bootstrap.php');






if () {
	echo "File is valid, and was successfully uploaded.\n";
} else {
	echo "Possible file upload attack!\n";
}


$db_c = $db->getDB();

$db_c->prepare()