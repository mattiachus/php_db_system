<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>CBA assignment 2 Flow 1 Semester 3 - Mattia Martini</title>
</head>

<body>
  
<!-- PICTURES LIST -->      
<h1>Your Images</h1>

<!-- Select the information I want to show about pictures from db -->    
<?php
	require_once('dbcon.php');
	$sql = 'SELECT id, url, title FROM pictures ORDER BY update_time DESC';
	$stmt = $link->prepare($sql);
	$stmt->execute();
	$stmt->bind_result($id, $url, $title);
    
	
	while($stmt->fetch()){ ?>
	<h2><?=$id?>: <?=$title?></h2>
	<a href="pictureinfo.php?id=<?=$id?>">
        <img src="<?=$url?>" width="100px" /></a>
    
<?php } ?>
    <hr>
    
<!-- CATEGORIES LIST --> 
<?php    
if($cmd = filter_input(INPUT_POST, 'cmd')){
// the button function 'cmd' calls both ADD and DELETE category, the difference in in the form's name 'add_category' and 'delete category'	
	if($cmd == 'add_category'){
		$category = filter_input(INPUT_POST, 'categoryname')
			or die('Missing/illegal categoryname parameter');
// INSERT INTO adds a new category to the DB	
		require_once('dbcon.php');
		$sql = 'INSERT INTO categories (category) VALUES (?)';
		$stmt = $link->prepare($sql);
		$stmt->bind_param('s', $category);
		$stmt->execute();
		
		if($stmt->affected_rows > 0){
			echo 'Category "'.$category.'" added';
		}
		else{
			echo 'Could not add the category';
		}		
	}
        elseif($cmd == 'delete_category'){

		
		$cid = filter_input(INPUT_POST, 'categories', FILTER_VALIDATE_INT)
			or die('Missing/illegal categoryID parameter');
// DELETE FROM is used to remove a category from the DB	
		require_once('dbcon.php');
		$sql = 'DELETE FROM categories WHERE category_id=?';
		$stmt = $link->prepare($sql);
		$stmt->bind_param('i', $cid);
		$stmt->execute();
// if statement that controls the category was deleted succesfully		
		if($stmt->affected_rows > 0){
			echo 'Category "'.$cid.'" deleted';
		}
		else{
			echo 'Could not delete category '.$cid;
		}			
        }
    }
?>    
    <h2>List of categories</h2>
<!-- Lists of categories dinamically generated in form of anchor links that open the single category page (picturelist.php) -->	
<?php
	require_once('dbcon.php');
	$sql = 'SELECT category_id, category FROM categories';
	$stmt = $link->prepare($sql);
	$stmt->execute();
	$stmt->bind_result($cid, $category);
    while($stmt->fetch()) { ?>

    <ul>
	<li><a href="picturelist.php?cid=<?=$cid?>"><?=$category?></a></li>
    </ul>    
<?php } ?>

<!-- the form to add and delete a new category to the DB (the php is above) -->    
<form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
	<fieldset>
    	<legend>Add new category</legend>
    	<input name="categoryname" type="text" placeholder="New category" required />
		<button name="cmd" value="add_category" type="submit">Create it!!!</button>
  	</fieldset>
</form>
<form action="<?=$_SERVER['PHP_SELF']?>" method="post">    
<!-- fancy selection panel showing the different categories (updated) -->   
    <select name="categories">
 <?php
        $sql = 'SELECT category_id, category FROM categories ORDER BY category_id DESC';
        $stmt = $link->prepare($sql);
        $stmt->execute();
        $stmt->bind_result($cid, $category);
        while ($stmt->fetch()){
        echo                  
        '<option value="'.$cid.'">'.$category.'</option>'.PHP_EOL;
    } ?>  
    </select>
        <button type="submit" value="delete_category" name="cmd">Delete category</button>
        </form>
    
   <hr> 

<!-- ADD PICTURE: most of the PHP happens in the upload.php page -->    
	<h1>Upload a new picture</h1>
	<form action="upload.php" method="post" enctype="multipart/form-data" id="pics">
    Select image to upload*:<br>
    	<input type="text" name="title" placeholder="Image title*" required />
    	<input type="file" name="fileToUpload" id="fileToUpload">
        <input type="submit" value="Upload Image" name="submit">
    </form><br>
    
    
    Select category*:    
        <select name="formCategory" form="pics" required>
        <?php
        $sql = 'SELECT * FROM categories ORDER BY category_id DESC';
    
        $stmt = $link->prepare($sql);
        $stmt->execute();
        $stmt->bind_result($cid, $category);
        while ($stmt->fetch()){
        echo                  
        '<option value="'.$cid.'">'.$category.'</option>'.PHP_EOL;
        } ?>     
        </select><br>
    Choose a tag*:    
        <select name="formTag" form="pics" required>
  <?php
        $sql = 'SELECT * FROM tags ORDER BY tag_id DESC';

        $stmt = $link->prepare($sql);
        $stmt->execute();
        $stmt->bind_result($tid, $tag);
        while ($stmt->fetch()){
        echo                  
        '<option value="'.$tid.'">'.$tag.'</option>'.PHP_EOL;
        } ?>  
        </select><br>
  <p> *Required fields </p> 
    <br>
	
</body>
</html>
