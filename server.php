<?php
$db = mysqli_connect("dvwebb.mah.se", "AE3529", "guldfisk1337", "ae3529");

$return = new ArrayObject();

if(isset($_FILES['image']['tmp_name'])){
	$path = "photo/";
	$fileName = $path.rand().$_FILES['image']['name'];

	if(move_uploaded_file($_FILES['image']['tmp_name'], $fileName)){
		if(mysqli_query($db, "INSERT INTO item (Title, Image, Description, CurrentBid) VALUES ('".$_POST['title']."', '".$fileName."', '".$_POST['desc']."', '10')")){
			$return['success'] = true;
			$return['message'] = "Fil uppladdad & sparad i db";
			header("Location: http://ddwap.mah.se/ae3529/dittemitt/");
		}else{
			$return['success'] = false;
			$return['message'] = "Fil uppladdad men inte sparad i db";
			echo json_encode($return);
		}

	}else{
		$return['success'] = false;
		$return['message'] = "Kunde inte ladda upp filen";
		echo json_encode($return);
	}
}

if(isset($_GET['item']) and $_GET['item'] == "getItem"){
	$res = mysqli_query($db, "SELECT * FROM item ORDER BY id DESC");

	$media = new ArrayObject();
	while($row = mysqli_fetch_assoc($res)){
		$m = new ArrayObject();
		$m['ID'] = $row['ID'];
		$m['Title'] = $row['Title'];
		$m['Image'] = $row['Image'];
		$m['Description'] = $row['Description'];
		$m['Datee'] = $row['Datee'];
		$m['CurrentBid'] = $row['CurrentBid'];
		$media['item'][] = $m;
	}
	echo json_encode($media);
}

if(isset($_GET['bidValue']) and $_GET['bidValue'] == $_GET['bidValue']){
	if(mysqli_query($db, "UPDATE item SET CurrentBid = '".$_GET['bidValue']."' WHERE id=1")){
		$return['success'] = true;
		$return['message'] = "Du har lagt ett nytt bud";
		echo json_encode($return);
	}else{
		$return['success'] = false;
		$return['message'] = "Slut på cash skaffa ett jobb";
		echo json_encode($return);
	}
}

?>