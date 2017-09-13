<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>CBA assignment 2 Flow 1 Semester 3 - Mattia Martini</title>
</head>

<body>
    
<a href="index.php">HOME</a><br> 

<?php
// here the code that selects the information about the selected pictures to be displayed    
	$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT)
		or die('Missing/illegal ID parameter')
?>
<?php
	require_once('dbcon.php');
	$sql = 'SELECT id, title, url FROM pictures where id=?';
	$stmt = $link->prepare($sql);
	$stmt->bind_param('i', $id);
	$stmt->execute();
	$stmt->bind_result($id, $title, $url);
	while($stmt->fetch()){ ?>
<!-- the result: -->    
		<img src="<?=$url?>" alt="<?=$title?>" width="600px" ><br> 
        <h2>Name: <?=$title?></h2> 		
<?php } ?>
    
<h2>Category:</h2>
<?php
// this SQL statement finds the category related to this particular image 
// the category is shown in the form of an anchor link that send you to the relative page
$sql = 'SELECT pictures.fk_category_id, categories.category
FROM pictures, categories
WHERE pictures.id=?
AND pictures.fk_category_id = categories.category_id';
$stmt = $link->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$stmt->bind_result($cid, $category);
while($stmt->fetch()) {
	echo '<h3><a href="picturelist.php?cid='.$cid.'">'.$category.'</a></h3>'.PHP_EOL;
}
?>
<hr>    

<h2>Tags:</h2>
<?php
// this SQL statement finds the tag related to this particular image 
// the tag is shown in the form of an anchor link that send you to the relative page
$sql = 'SELECT pictures.fk_tag_id, tags.tag
FROM pictures, tags
WHERE pictures.id=?
AND pictures.fk_tag_id = tags.tag_id';
$stmt = $link->prepare($sql);
$stmt->bind_param('i',$id);    
$stmt->execute();
$stmt->bind_result($tid, $tag);
while($stmt->fetch()) {
	echo '<h3>'.$tid.': '.$tag.'</h3>'.PHP_EOL;
}
?>
<!-- NOTE the tag feature is not very relevant at this point, the plan is to be able to add multiple tags to a single picture -->   

<hr>
   
<!-- RENAME PICTURE --> 
<!-- hitting the link we got send to another page where we're able to change the name of the picture-->
    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
        <input type="hidden" name="id" value="<?=$id?>" />
    </form>
    <h3><a href="rename_picture.php?id=<?=$id?>">Rename this picture</a></h3>
<br>
</body>
</html>