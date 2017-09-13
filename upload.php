<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>CBA assignment 2 Flow 1 Semester 3 - Mattia Martini</title>
</head>

<body>
    
<?php
    
// information to upload in the database from the form in page index.php    
$title = filter_input(INPUT_POST, 'title')
	or die('Missing/illegal title parameter!!!');
$cid = filter_input(INPUT_POST, 'formCategory');
$tid = filter_input(INPUT_POST, 'formTag');    
$target_dir = "img/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	
// Function that checka if image file is an actual image or fake image

if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}
if(isset($_POST["submit"])){    
    $category = $_POST ['formCategory'];
    $tag = $_POST ['formTag']; 
}
// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 5000000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow only certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";

}
    
// if everything is ok, try to upload file
else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "<p>The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.</p>";
		
		// this line was to check if all the parameters where binded
        // echo "<p> title: ".$title." url: " .$target_file. " categoryid: " .$cid. " tagid: ".$tid."</p>";
        
		require_once('dbcon.php');
//let's insert those parameters in the DB!		
		$sql = 'INSERT INTO pictures (title, url, fk_category_id, fk_tag_id) VALUES (?,?,?,?)';
		$stmt = $link->prepare($sql);
		$stmt->bind_param('ssii', $title, $target_file, $cid, $tid);
		$stmt->execute();
		if ($stmt->affected_rows > 0) {
			echo '<p>Filedata added to the database :-)</p>';
		}
		else {
			echo '<p>Could not add the file to the database :-(</p>';
		}
		
    } else {
        echo "<p>Sorry, there was an error uploading your file.</p>";
    }
}
?>
<hr>
	<a href="index.php">Go back</a><br>

</body>
</html>