<?php
$servername = "url";
$username = "username";
$password = "password";

try {
    $db = new PDO("mysql:host=$servername;dbname=interactive_map", $username, $password);
    $objectList = array();
	if($_GET["format"] == "room"){
	    $statement = $db->prepare("SELECT `object_id` AS `objectID`, `x`, `y`, `orientation`, `type`, `object_class` AS `objectClass`, width, height, `coor_str` AS `coorStr`, 'room' AS `format` FROM `rooms`");
	    $statement->execute();
	    $primaryResults = $statement->fetchAll(PDO::FETCH_ASSOC);
	
	    $statement = $db->prepare("SELECT `room_id`, `display`, `text_x` AS `textX`, `text_y` AS `textY`, `text_width` AS `textWidth`, `display_name` AS `displayName`, `full_name` AS `fullName`, `extension` AS 'phoneNum' FROM `room_info_sets` ORDER BY `order`");
	    $statement->execute();
	    $secondaryResults = $statement->fetchAll(PDO::FETCH_ASSOC);

		for($i = 0; $i < count($primaryResults); $i++){
			$secondaryObjectList = array();
			for($j = 0; $j < count($secondaryResults); $j++){
				if($secondaryResults[$j]["room_id"] == $primaryResults[$i]["objectID"]){
					$setDuplicate = $secondaryResults[$j];
					unset($setDuplicate["room_id"]);
					$secondaryObjectList = array_merge($secondaryObjectList, array($setDuplicate));
				}
			}
			$rootObject = array($primaryResults[$i]["objectID"] => array_merge($primaryResults[$i], array("infoSets"=> $secondaryObjectList)));
			$objectList = array_merge($objectList, $rootObject);
	
		}
	    
	}
	if($_GET["format"] == "wall"){
	    $statement = $db->prepare("SELECT `object_id` AS `objectID`, `x`, `y`, `orientation`, `object_class` AS `objectClass`, `coor_str` AS `coorStr`, 'wall' AS `format` FROM `walls` ORDER BY z_index");
	    $statement->execute();
	    $primaryResults = $statement->fetchAll(PDO::FETCH_ASSOC);

		for($i = 0; $i < count($primaryResults); $i++){
			$rootObject = array($primaryResults[$i]["objectID"] => $primaryResults[$i]);
			$objectList = array_merge($objectList, $rootObject);
		}
	}
	if($_GET["format"] == "supportBeam"){
	    $statement = $db->prepare("SELECT `object_id` AS `objectID`, `x`, `y`, `orientation`, `width`, `height`, 'supportBeam' AS `format` FROM `support_beams`");
	    $statement->execute();
	    $primaryResults = $statement->fetchAll(PDO::FETCH_ASSOC);

		for($i = 0; $i < count($primaryResults); $i++){
			$rootObject = array($primaryResults[$i]["objectID"] => $primaryResults[$i]);
			$objectList = array_merge($objectList, $rootObject);
		}
	}
	if($_GET["format"] == "printer"){
	    $statement = $db->prepare("SELECT `object_id` AS `objectID`, `x`, `y`, `display`, `full_name` AS `fullName`, `model`, 'printer' AS `format` FROM `printers`");
	    $statement->execute();
	    $primaryResults = $statement->fetchAll(PDO::FETCH_ASSOC);

		for($i = 0; $i < count($primaryResults); $i++){
			$rootObject = array($primaryResults[$i]["objectID"] => $primaryResults[$i]);
			$objectList = array_merge($objectList, $rootObject);
		}
	}
	if($_GET["format"] == "furniture"){
	    $statement = $db->prepare("SELECT `object_id` AS `objectID`, `x`, `y`, `orientation`, `coor_str` AS `coorStr`, 'furniture' AS `format` FROM `furnitures`");
	    $statement->execute();
	    $primaryResults = $statement->fetchAll(PDO::FETCH_ASSOC);

		for($i = 0; $i < count($primaryResults); $i++){
			$rootObject = array($primaryResults[$i]["objectID"] => $primaryResults[$i]);
			$objectList = array_merge($objectList, $rootObject);
		}
	}
	if($_GET["format"] == "filingCabinet"){
	    $statement = $db->prepare("SELECT `object_id` AS `objectID`, `x`, `y`, `orientation`, `width`, `height`, `rows`, `columns`, 'filingCabinet' AS `format` FROM `filing_cabinets`");
	    $statement->execute();
	    $primaryResults = $statement->fetchAll(PDO::FETCH_ASSOC);

		for($i = 0; $i < count($primaryResults); $i++){
			$rootObject = array($primaryResults[$i]["objectID"] => $primaryResults[$i]);
			$objectList = array_merge($objectList, $rootObject);
		}
	}
	echo json_encode($objectList);
    $dsn = null;
}
catch(PDOException $e)
    {
    echo "Error: " . $e->getMessage();
}
$conn = null;
?>