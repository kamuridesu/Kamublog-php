<?php
  session_start();
  if (!(isset($_SESSION['email']))){
    header("Location: login.php");
  }
  class Register{
    public $reg_error;
    public $usr_post_data;

    public function check_usr_input(){
      $required = array("username", "email", "password", "password2");
      $err = false;
      foreach($required as $field){
        if(empty($_POST[$field])){
          $err = true;
        }
      }
      if($err){
        $this->reg_error = "Erro! Verifique se todos os campos estão preenchidos!";
        return false;
      }
      if($_POST['password'] !== $_POST['password2']){
        $this->reg_error = "Erro! Verifique se as senhas são iguais!";
        return false;
      }
      $this->usr_post_data = array($_POST['username'], $_POST["email"], $_POST["password"]);
      return true;
    }

    function save_on_db(){
      $conn = new mysqli("127.0.0.1", "root", "", "test");
      if($conn->connect_error){
        die("Erro ao conectar com o banco de dados: " . $conn->connect_error);
      }
      $uemail = $this->usr_post_data[1];
      $uname = $this->usr_post_data[0];
      $sql = "SELECT id, email, username, passwd FROM users WHERE email = '$uemail'";
      $result = $conn->query($sql);
      if($result->num_rows > 0){
        $this->reg_error = "Erro! Esse email já está em uso!";
        return false;
      }
      $upasswd = password_hash($this->usr_post_data[2], PASSWORD_BCRYPT);
      $sql = "INSERT INTO users (`email`, `username`, `passwd`) VALUES ('$uemail', '$uname', '$upasswd')";
      if($conn->query($sql) === true){
        $conn->close();
        header("Location: ./index.php?user=success");
      } else {
        $conn->close();
        $this->reg_error = "Algum erro ocorreu";
        return false;
      }
    }

    function register(){
      if($this->check_usr_input()){
        if(!($this->save_on_db())){
          return false;
        }
        return true;
      }
    }
  }

  $reg_usr = new Register();
  if($_SERVER["REQUEST_METHOD"] == "POST"){
    if($reg_usr->register()){
      $reg_usr->reg_error = "Usuário adicionado com sucesso";
    }
  }
  
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Painel de Administração</title>
  <style>

    ::placeholder {
      color: blue;
      opacity: 1;
    }

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
  <link rel="stylesheet" type="text/css" href="./css/materialize.css">
</head>
<body>
  <?php include_once('./header.php'); ?>
<main>
    <center>
      <div class="container">
        <div class="row" style="margin-left: 20%">

          <form class="col s12" action="" method="post">
            <div class='row'>
              <div class='col s12'>
              </div>
            </div>

            <div class='row'>
              <div class='input-field col s12'>
                <input placeholder="Digite seu nome de usuario" class='validate' type='text' name='username' id='username' />
              </div>
            </div>

            <div class='row'>
              <div class='input-field col s12'>
                <input placeholder="Digite seu email" class='validate' type='email' name='email' id='email' />
              </div>
            </div>

            <div class='row'>
              <div class='input-field col s12'>
                <input placeholder="Digite uma senha" class='validate' type='password' name='password' id='password' />
              </div>
            </div>

            <div class='row'>
              <div class='input-field col s12'>
                <input placeholder="Digite uma senha novamente" class='validate' type='password' name='password2' id='password2' />
              </div>
            </div>

            <br />
            <center>
              <div class='row'>
                <button type='submit' name='btn_login' class='col s12 btn btn-large waves-effect gray'>REGISTRAR</button>
              </div>
            </center>
          </form>
        </div>
      </div>
    </center>
    <div class="section"></div>
    
    <div class="section"></div>
  </main>
  <script src="./js/materialize.js"></script>
  <?php if(isset($reg_usr->reg_error)){echo "<script>document.addEventListener('DOMContentLoaded', function() { M.toast({html: '$reg_usr->reg_error'});});</script>";} ?>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      var elems = document.querySelectorAll('.collapsible');
      var instances = M.Collapsible.init(elems);
    });

    document.addEventListener('DOMContentLoaded', function() {
      var elems = document.querySelectorAll('.sidenav');
      var instances = M.Sidenav.init(elems);
    });
  </script>
</body>
</html>