<?php
  ob_start();
  $passwd_err;
  $field_err;
  $user_err;

  session_start();
  if (!(isset($_SESSION['email']))){
    header("Location: login.php");
  }

  function register_user(){
    if (isset($_POST)){
      $required = array("username", "email", "password", "password2");
      $err = false;
      foreach($required as $field){
        if (empty($_POST[$field])){
          $err = true;
        }
      }
      if ($err){
        return -1;
      } else{
        $uname = $_POST['username'];
        $uemail = $_POST['email'];
        $upasswd = $_POST['password'];
        $upasswd2 = $_POST['password2'];
        if ($upasswd === $upasswd2){
          return array($uname, $uemail, $upasswd);  
        } else {
          global $passwd_err;
          $passwd_err = "Erro! Verifique se as senhas são iguais";
          return -2;
        }
        
      }
    }
  }

  function saveToDB($uname, $uemail, $upasswd){
    $conn = new mysqli("127.0.0.1","root", "","test");
    if($conn->connect_error){
      die("A conexão com o banco de dados falhou falhou: " . $conn->connect_error);
    }
    $upasswd = password_hash($upasswd, PASSWORD_BCRYPT);

    $sql = "SELECT id, email, username, passwd FROM users";
    $result = $conn->query($sql);
    if ($result->num_rows > 0){
      while ($row = $result->fetch_assoc()){
        $dbemail = $row['email'];
        $dbname = $row['username'];
        if ($dbname === $uname && $dbemail === $uemail){
          global $user_err;
          $user_err = "Erro! Esse usuário já existe!";
          return -2;
        }
      }
    }
    $sql = "INSERT INTO users (`email`, `username`, `passwd`) VALUES ('$uemail', '$uname', '$upasswd')";
    if ($conn->query($sql) === true){
      echo "Usuário salvo com sucesso!";
    } else {
      global $passwd_err;
      $passwd_err =  "Um erro ocorreu: " . $sql . "<p>" . $conn->error . "</p>";
    }
    $conn->close();
    return 0;
  }

  function initialize(){
    $res = register_user();
    if ($res === -1){
      global $field_err;
      $field_err = "Erro! Verifique se todos os campos estão preenchidos!";
    } else if (!($res === -2)){
      $uname = $res[0];
      $uemail = $res[1];
      $upasswd = $res[2];
      $success = saveToDB($uname, $uemail, $upasswd);
      if ($success === 0){
        header("Location: index.php");
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
  <header>
    <nav class="shiro-header">
      <div class="nav-wrapper">
        <a href="/Kamublog/admin/index.php" class="brand-logo center" style="color: black;">Painel de Administração - Registro</a>
      </div>
    </nav>
    
    <ul class="sidenav sidenav-fixed aoi-header" id=slide-out style="background-color: gray;">
      <div class="child-sidenav">
        <li> <a href="/Kamublog/admin/index.php" class="center" style="font-size: 50%; color: white;"><b><?php echo $_SESSION['username']; ?></b></a> </li>
        <ul class="collapsible">
          <li>
            <div class="collapsible-header center" style="font-size: 40%; color: white;"><b>DASHBOARD</b></div>
            <div class="collapsible-body child-collapsi"><span></span></div>
          </li>
          <li>
            <div class="collapsible-header center" style="font-size: 40%; color: white;"><b>POSTS</b></div>
            <div class="collapsible-body child-collapsi center"><span><a href="/Kamublog/admin/posts/add.php" style="color: black;">ADICIONAR POST</a></span></div>
            <div class="collapsible-body child-collapsi center"><span>EDITAR POST</span></div>
            <div class="collapsible-body child-collapsi center"><span>REMOVER POST</span></div>
          </li>
          <li>
            <div class="collapsible-header" class="center" style="font-size: 40%; color: white;"><b>ACCOUNTS</b></div>
            <div class="collapsible-body child-collapsi center"><a href="/Kamublog/admin/register.php" style="color: black;">ADICIONAR USUÁRIO</a></div>
            <div class="collapsible-body child-collapsi center"><a href="/Kamublog/admin/logout.php" style="color: black;">LOGOUT</a></div>
          </li>
        </ul>
      </div>
    </ul>
                                                                                         
    <a href="#" data-target="slide-out" class="sidenav-trigger"></a>
  </header>
<main>
    <center>
      <p style="color:red;">  <?php if (isset($passwd_err)){echo $passwd_err;} ?> </p>
      <p style="color:red;">  <?php if (isset($field_err)){echo $field_err;} ?> </p>
      <p style="color:red;">  <?php if (isset($user_err)){echo $user_err;} ?> </p>

      <div class="container">
        <div class="z-depth-1 grey lighten-4 row" style="display: inline-block; padding: 32px 48px 0px 48px; border: 1px solid #EEE;">

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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
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