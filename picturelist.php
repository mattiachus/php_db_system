<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>CBA assignment 2 Flow 1 Semester 3 - Mattia Martini</title>
</head>

<body>
<a href="index.php">HOME</a><br> 

<?php
// this code is to show on which category we are at the moment    
	$cid = filter_input(INPUT_GET, 'cid', FILTER_VALIDATE_INT)
		or die('Missing/illegal categoryid parameter');
    
    require_once('dbcon.php');
	$sql = 'SELECT category FROM categories where category_id=?';
    $stmt = $link->prepare($sql);
    $stmt->bind_param('i',$cid);
    $stmt->execute();
    $stmt->bind_result($category);
    while($stmt->fetch()){
    echo '<h1>Pictures in "'.$category.'"</h1>';
 } 
?>
<?php
// the following SQL statement SELECTs all the pictures related to a certain category ID    
	require_once('dbcon.php');
	$sql = 'SELECT pictures.id, pictures.url, categories.category
			FROM pictures, categories
			WHERE pictures.fk_category_id=categories.category_id AND categories.category_id=?';
	$stmt = $link->prepare($sql);
	$stmt->bind_param('i', $cid);
	$stmt->execute();
	$stmt->bind_result($id, $url, $category);
	while($stmt->fetch()){ ?>
    
<!-- The images shown in the gallery are also link to the single picture page -->		
    <a href="pictureinfo.php?id=<?=$id?>">
        <img src="<?=$url?>" width="300px"  /></a>
		
<?php } ?>
<hr>
<!-- The following link sends to a page where is possible to rename the category (rename_category.php) -->    
   <a href="rename_category.php?cid=<?=$cid?>">Rename this category</a>


</body>
</html>