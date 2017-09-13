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
	if($cmd == 'rename_category'){
		
		$cid = filter_input(INPUT_POST, 'cid', FILTER_VALIDATE_INT)
			or die('Missing/illegal CategoryID parameter');
		$name = filter_input(INPUT_POST, 'categoryname')
			or die('Missing/illegal categoryname parameter');
		
		require_once('dbcon.php');
		$sql = 'UPDATE categories SET category=? WHERE category_id=?';
		$stmt = $link->prepare($sql);
		$stmt->bind_param('si', $name, $cid);
		$stmt->execute();
		
		if($stmt->affected_rows > 0){
			echo 'Category name changed!!!';
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
?>

    
<?php
// the next part of php is just to show the current information	about the category
	if(empty($cid)){
		$cid = filter_input(INPUT_GET, 'cid', FILTER_VALIDATE_INT)
			or die('Missing/illegal categoryid parameter');
	}
	
	require_once('dbcon.php');
	$sql = 'SELECT category FROM categories WHERE category_id=?';
	$stmt = $link->prepare($sql);
	$stmt->bind_param('i', $cid);
	$stmt->execute();
	$stmt->bind_result($name);
	while($stmt->fetch()) {}
	
	?>

<!-- And here is our rename form  -->    
<h1>Rename "<?=$name?>"</h1><br>	
<form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
	<fieldset>
    	<legend>Rename category</legend>
    	<input name="cid" type="hidden" value="<?=$cid?>" />
    	<input name="categoryname" type="text" value="<?=$name?>" placeholder="Categoryname" required />
		<button name="cmd" value="rename_category" type="submit">Rename it!!!</button>
  	</fieldset>
</form>

	
	<hr>
	<a href="picturelist.php?cid=<?=$cid?>">Visualize picture from <?=$name?></a>
</body>
</html>