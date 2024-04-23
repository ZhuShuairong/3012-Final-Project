<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <title>Blob File MySQL</title>
</head>
<body>
  <?php  
    $dbh = new PDO("mysql:host=localhost;dbname=mydata", "root", "A12345678");
     if (isset($_POST['btn'])) {
      $fname = $_FILES['myfile']['name'];
      $ftype = $_FILES['myfile']['type'];    
      $uploadfile = $_FILES['myfile']['tmp_name'];
      $file_data = file_get_contents($uploadfile);
      $stm = $dbh->prepare("insert into myshop values(' ', ?, ?, ?)");
      $stm->bindValue(1, $fname);
      $stm->bindValue(2, $ftype);
      $stm->bindValue(3, $file_data);
      $stm->execute();       
    }
  ?>
  <form method=post enctype="multipart/form-data">
    <input type=file name=myfile>
    <button name=btn>Upload</button>
  </form>
</body>
</html>
