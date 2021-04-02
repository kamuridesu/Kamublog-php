<?php
    if(!isset($_SESSION['email'])){
        header("Location: /Kamublog/admin/login.php");
    }
    if (basename(__FILE__) == basename($_SERVER["SCRIPT_FILENAME"])){
        header("Location: /Kamublog/admin/index.php");
    }
?>

<header>
  <nav class="shiro-header">
    <div class="nav-wrapper">
      <a href="/Kamublog/admin/index.php" class="brand-logo center" style="color: black; font-size: 200%">Painel de Administração</a>
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
          <div class="collapsible-body child-collapsi center"><span><a href="/Kamublog/admin/posts/posts.php" style="color: black;">EDITAR POST</a></span></div>
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