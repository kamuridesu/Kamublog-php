<?php
  session_start();
  if (!isset($_SESSION['email'])){
    header("Location: login.php");
  }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <link rel="stylesheet" type="text/css" href="./css/materialize.css">
  
  <title>Painel de Administração</title>

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
        <a href="/Kamublog/admin/index.php" class="brand-logo center" style="color: black;">Blog - <?php echo $_SESSION['username']; ?></a>
      </div>
    </nav>
    
    <ul class="sidenav sidenav-fixed aoi-header" id=slide-out>
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