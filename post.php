<?php
  class Blogpost{
    public $post_data;
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
        $this->post_data = $row;
        return true;
      }
      return false;
    }
    function echo_img(){
      if(!($this->post_data['post_image'] === "")){
        $img = $this->post_data['post_image'];
        echo '<img src="/Kamublog/admin/functions/getImage.php?file=' . $img . '" height="70%" width="70%">';
      }
    }
  }
  

  $post_instance = new Blogpost();
  $post_instance->retrieve_data($actual_slug);

  $data = $post_instance->post_data;
  //print_r($data);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="/Kamublog/css/materialize.css">
  <title><?php echo $data['post_name'] ?></title>

  <style>
    .aoi-header{
      background-color: gray;
      font-size: 200%;
      color: white;
    }
    .shiro-header{
      background-color: white;
      color: black;
    }

    .child-sidenav{
      font-size: 200%;
      color: gray;
    }

    .child-collapsi{
      font-size: 25%;
      color: black;
    }

  </style>
</head>
<body>
  <header>
    <nav class="shiro-header">
      <div class="nav-wrapper">
        <a href="/Kamublog/admin/index.php" class="brand-logo center" style="color: black; font-size: 200%">Kamublog</a>
      </div>
    </nav>
  </header>
  <main>
    <div class="center">
    </div>
    <div class="container hide-on-med-and-down">
      <div class="s12 m8 l8 center-align">

        <h3><?php echo $data['post_name'] ?></h3>
        <?php $post_instance->echo_img() ?>
        <p style="font-size:70%" class="right-align"><?php echo $data['post_date'] ?></p>
        <?php echo $data['post_content'] ?>
      </div>
    </div>
      <div class="container hide-on-large-only">
        <div class="s12 m8 l8 center-align">
          <h3><?php echo $data['post_name'] ?></h3>
          <?php $post_instance->echo_img() ?>
          <p style="font-size:70%" class="right-align"><?php echo $data['post_date'] ?></p>
          <?php echo $data['post_content'] ?>
        </div>
      </div>
  </main>
</body>
</html>