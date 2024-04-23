<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Role Register</title>
</head>
<body>
<h2>Choose Your Role Appearance</h2>

<form action="index.php" method="post">


    <label>Select Your Role Appearance:</label><br>

    <?php
    // Array of image file names
    $images = ["role1.jpg", "role2.jpg", "role3.jpg"];

    // Loop through the images array to generate radio buttons with corresponding images
    foreach ($images as $image) {
        echo '<input type="radio" id="'.$image.'" name="role" value="'.$image.'" required>';
        echo '<label for="'.$image.'"><img src="'.$image.'" alt="Role"></label><br>';
    }
    ?>

    <input type="submit" value="Register">
</form>

</body>
</html>
