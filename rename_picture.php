<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>CBA assignment 2 Flow 1 Semester 3 - Mattia Martini</title>
</head>

<body>

<?php
// the function that UPDATEs the information in the DB    
	
if($cmd = filter_input(INPUT_POST, 'cmd')){
	if($cmd == 'rename_picture'){
		
		$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT)
			or die('Missing/illegal ID parameter');
		$title = filter_input(INPUT_POST, 'title')
			or die('Missing/illegal TITLE parameter');
		
		require_once('dbcon.php');
		$sql = 'UPDATE pictures SET title=? WHERE id=?';
		$stmt = $link->prepare($sql);
		$stmt->bind_param('si', $title, $id);
		$stmt->execute();
		
		if($stmt->affected_rows > 0){
			echo 'Name changed!!!';
		}
  //error messages      
		else{
			echo 'Nothing was changed ?!?!?!';
		}
	}
	else {
		die('Unknown cmd parameter');
	}
}
// the next part of php is just to show the picture and its current name in the page	
	if(empty($id)){
		$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT)
			or die('Missing/illegal ID parameter');
	}
	
	require_once('dbcon.php');
	$sql = 'SELECT title, url FROM pictures WHERE id=?';
	$stmt = $link->prepare($sql);
	$stmt->bind_param('i', $id);
	$stmt->execute();
	$stmt->bind_result($title, $url);
	while($stmt->fetch()) {}
	
	?>
<h1>Rename "<?=$title?>"</h1>    
<img src="<?=$url?>" alt="x" width="300px">	

<!-- and here we have the form that allows you to modify the name ($title) of a picture based on the id -->    
<form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
	<fieldset>
    	<legend>Rename picture</legend>
    	<input name="id" type="hidden" value="<?=$id?>" />
    	<input name="title" type="text" value="<?=$title?>" placeholder="title" required />
		<button name="cmd" value="rename_picture" type="submit">Rename it!!!</button>
  	</fieldset>
</form>

	
	<hr>
    <a href="pictureinfo.php?id=<?=$id?>">View picture page</a><br>
	<a href="index.php">View home page</a>
</body>
</html>