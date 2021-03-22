<?php
  session_start();
	if(!(isset($_SESSION['email']))){
    header("Location: ../login.php");
  }

  function initialize(){
      if (isset($_POST["publish"])){
        echo $_POST["articlebody"];
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
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <link rel="stylesheet" type="text/css" href="../css/materialize.css">
  
  <title>Adicionar Post</title>

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
        <a class="brand-logo center" style="color: black;">Adicionar Post</a>
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
                                                                                         
    <a href="#" data-target="slide-out" class="sidenav-trigger">menu</a>
  </header>

  <main>
    <div class="section"></div>
    <center class="container" style="margin-left: 25%">
      <form class="col s12" action="" method="post">
          <div class="row">
            <div class="row">
              <div class="input-field col s12">
                <textarea name="articlename" id="articlename" class="materialize-textarea validate"></textarea>
                <label class="active" for="articlename">Título</label>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="row">
              <div class="input-field col s12">
                <input name="date" id="date" type="text" class="datepicker validate">
                <label class="active" for="date">Data de publicação</label>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="row">
              <div class="input-field col s12">
                <input value="<?php echo $_SESSION['username']; ?>" name="author" id="author" type="text" class="validate active">
                <label class="active" for="author">Autor</label>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="row">
              <div class="input-field col s12">
                <textarea name="articlebody" id="articlebody" class="materialize-textarea"></textarea>
              </div>
            </div>
          </div>

          <div class='row'>
            <button type='submit' id="save" name='save' class='btn waves-effect gray left' style="margin-right: 3%">Salvar</button>
            <button type='submit' id="publish" name='publish' class='btn waves-effect gray left'>Publicar</button>
          </div>
      </form>
    </center>
  </main>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
  <script src="ckeditor/ckeditor.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      var elems = document.querySelectorAll('.collapsible');
      var instances = M.Collapsible.init(elems);
    });

    document.addEventListener('DOMContentLoaded', function() {
      var elems = document.querySelectorAll('.sidenav');
      var instances = M.Sidenav.init(elems);
    });

    var datetimeopt = {
      i18n: {
        cancel: 'Cancelar',
        clear: 'Limpar',
        done: 'OK',
        close: 'Fechar',
        default: 'now',
        setDefaultDate: true,
        today: 'Hoje',
        closeOnSelect: false,
        format: 'dd-mmm-yyyy',
        selectMonths: true,
        previousMonth: '<',
        nextMonth: '>',
        months: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
        monthsShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dec'],
        firstDay: true,
        weekdays: ["Domingo", "Segunda", "Terça", "Quarta", "Quinta", "Sexta", "Sábado"],
        weekdaysShort: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sab"],
        weekdaysAbbrev: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S']
      }
    };
    document.addEventListener('DOMContentLoaded', function() {
      var elems = document.querySelectorAll('.datepicker');
      var instances = M.Datepicker.init(elems, datetimeopt);
    });

    M.updateTextFields();

    CKEDITOR.replace('articlebody');
  </script>
</body>
</html>