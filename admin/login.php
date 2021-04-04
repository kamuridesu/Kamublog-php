<?php
  session_start();
  if(isset($_SESSION['email'])){
    header("Location: ./index.php");
  }
  class Login{
    public $login_error;
    public $passwd;
    public $email;
    public $usr_data;
    public function check_usr_inpt(){
      if(isset($_POST['email']) && isset($_POST['password'])){
        $this->passwd = $_POST['password'];
        $this->email = $_POST['email'];
        return true;
      } else {
        $this->login_error = "Erro! Verifique todos os campos estão preenchidos!";
        return false;
      }
    }

    public function check_db(){
      $conn = new mysqli("127.0.0.1", "root", "", "test");
      if($conn->connect_error){
        die("Ocorreu um erro ao conectar com o banco de dados! " . $conn->connect_error);
      }
      $sql = "SELECT id, email, username, passwd FROM users WHERE email = '$this->email'";
      $result = $conn->query($sql);
      if($result->num_rows > 0){
        $row = $result->fetch_assoc();
        if(password_verify($this->passwd, $row['passwd'])){
          $this->usr_data = $row;
          $conn->close();
          return true;
        }
      }
      $conn->close();
      $this->login_error = "Erro! Verifique se email ou senha estão certos!";
      return false;
    }

    public function login(){
      if($this->check_usr_inpt()){
        if($this->check_db()){
          $_SESSION['id'] = $this->usr_data['id'];
          $_SESSION['email'] = $this->usr_data['email'];
          $_SESSION['username'] = $this->usr_data['username'];
          header("Location: ./index.php");
        }
      }
    }
  }
  $login_strt = new Login();
  if($_SERVER["REQUEST_METHOD"] == "POST"){
    $login_strt->login();
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
    body {
      display: flex;
      min-height: 100vh;
      flex-direction: column;
    }

    main {
      flex: 1 0 auto;
    }

    body {
      background: #fff;
    }

    .input-field input[type=date]:focus + label,
    .input-field input[type=text]:focus + label,
    .input-field input[type=email]:focus + label,
    .input-field input[type=password]:focus + label {
      color: #e91e63;
    }

    .input-field input[type=date]:focus,
    .input-field input[type=text]:focus,
    .input-field input[type=email]:focus,
    .input-field input[type=password]:focus {
      border-bottom: 2px solid #e91e63;
      box-shadow: none;
    }

    ::placeholder {
      color: blue;
      opacity: 1;
    }
  </style>
  <link rel="stylesheet" type="text/css" href="./css/materialize.css">
</head>
<body>
<main>
    <center>
      <div class="section"></div>
      <h5 class="gray-text">Painel de Administração - LOGIN</h5>

      <p style="color:red;">  <?php if(isset($login_strt->login_error)) { echo $login_strt->login_error; } ?> </p>

      <div class="container">
        <div class="z-depth-1 grey lighten-4 row" style="display: inline-block; padding: 32px 48px 0px 48px; border: 1px solid #EEE;">

          <form class="col s12" action="" method="post">
            <div class='row'>
              <div class='col s12'>
              </div>
            </div>

            <div class='row'>
              <div class='input-field col s12'>
                <input placeholder="Digite seu email" class='validate' type='email' name='email' id='email' />
              </div>
            </div>

            <div class='row'>
              <div class='input-field col s12'>
                <input placeholder="Digite sua senha" class='validate' type='password' name='password' id='password' />
              </div>
            </div>

            <br />
            <center>
              <div class='row'>
                <button type='submit' name='btn_login' class='col s12 btn btn-large waves-effect gray'>Login</button>
              </div>
              <label class="center">
								<a class='pink-text center' href='#!'><b>Esqueceu a senha?</b></a>
							</label>
              <div class="section"></div>
            </center>
          </form>
        </div>
      </div>
    </center>
    <div class="section"></div>
    <div class="section"></div>
  </main>
  <script type="text/javascript" src="./js/materialize.js">
</body>
</html>