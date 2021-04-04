<?php
  session_start();
	if(!(isset($_SESSION['email']))){
    //header("Location: ../login.php");
  }

  // global Bariables
  $success = false;
  $gb_err = false;
  $mensagem;

  function retrieve_data($slug){
    $conn = new mysqli("127.0.0.1","root", "","test");
    if($conn->connect_error){
      die("A conexão com o banco de dados falhou : " . $conn->connect_error);
    }

    $sql = "SELECT post_id, post_name, post_date, post_author, post_content, published, post_slug, post_image FROM posts WHERE post_slug = '$slug'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0){
      $row = $result->fetch_assoc();
      $conn->close();
      return $row;
    }
  }
  
  function initialize() {
    $data = retrieve_data($_GET['slug']);
    return $data;
  }

  $data = initialize();
  print_r($data);
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <main>
    <?php if(!($data['post_image'] === "")){
        $img = $data['post_image'];
        echo '<img src="/Kamublog/admin/functions/getImage.php?file=' . $img . '" height="70%" width="70%">';
       }
    ?>
  </main>
</body>
</html>