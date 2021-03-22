<?php
  ob_start();
  $passwd_err;
  function set_session($arr){
    session_start();
    $_SESSION['username'] = $arr[1];
    $_SESSION['email'] = $arr[2];
  }

  function login(){
      if (isset($_POST)){
        if(!isset($_SESSION)){
          $required = array("email", "password");
          $err = false;
          foreach($required as $field){
            if (empty($_POST[$field])){
              $err = true;
            }
          }
          if ($err){
            global $passwd_err;
            $passwd_err = "Erro! Verifique a senha e tente novamente!";
            return -1;
          } else {
            return array($_POST['email'], $_POST['password']);
        }
    	} 
    }
  }

  function retrieve_data($arr){
    if (isset($arr)){
      $conn = new mysqli('127.0.0.1', 'root', '', 'test');
      if($conn->connect_error){
        die("Ocorreu um erro de conexão: " . $conn->connect_error);
      }
      $sql = "SELECT id, email, username, passwd FROM users";
      $result = $conn->query($sql);
      if ($result->num_rows > 0){
        while ($row = $result->fetch_assoc()){
          $dbemail = $row['email'];
          $dbpasswd = $row['passwd'];
          if ($dbemail === $arr[0] && password_verify($arr[1], $dbpasswd)){
            return $row;
          }
        }
        return -1;
      } else {
        global $passwd_err;
        $passwd_err = "Erro! Verifique se o usuário existe!";
      }
      $conn->close();
    }
  }

  function ok() {
    header("Location: index.php");
  }

  function initialize(){
    $res = login();
    if (!($res === -1)){
      $udata = retrieve_data($res);
      if ($udata === -1){
        global $passwd_err;
        $passwd_err = "Erro! Verifique a senha e o email e tente novamente!";;
      } else if(is_null($udata)) {
        global $passwd_err;
        $passwd_err = "Erro! Verifique se os dados estão corretos!";
      } else {
        $uid = $udata['id'];
        $uname = $udata['username'];
        $umail = $udata['email'];
        set_session(array($uid, $uname, $umail));
        $passwd_err = "";
        ok();
      }
    }
  }
  if($_SERVER["REQUEST_METHOD"] == "POST"){
    initialize();
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

      <p style="color:red;">  <?php if (isset($passwd_err)){echo $passwd_err;} ?> </p>

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