<?php
  session_start();
	if(!(isset($_SESSION['email']))){
    header("Location: ../login.php");
  }

  // global Bariables
  $success = false;
  $gb_err = false;
  $mensagem;

  function retrieve_data($slug){
    $conn = new mysqli("127.0.0.1","root", "","test");
    if($conn->connect_error){
      die("A conexão com o banco de dados falhou falhou: " . $conn->connect_error);
    }

    $sql = "SELECT post_name, post_date, post_author, post_content, published, post_slug, post_image FROM posts";
    $result = $conn->query($sql);
    if ($result->num_rows > 0){
      while ($row = $result->fetch_assoc()){
        if($row['post_slug'] === $slug){
          return $row;
        }
      }
    }
    $conn->close();
  }
  
  function initialize() {
    $data = retrieve_data($_GET['slug']);
    return $data;
  }

  function unpack_date($date_string){
    // Mmm dd, yyyy
    $date_string = explode(" ", $date_string)[0];
    $months = array('Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dec');
    $unpkd = explode("-", $date_string);
    $month = $months[(int)$unpkd[1] - 1];
    $day = $unpkd[2];
    $year = $unpkd[0];
    $full_date = $month . " ". $day . ", " . $year;
    return $full_date;
  }

  $data = initialize();
  $unpacked_date = unpack_date($data['post_date']);
  print_r($data);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <link rel="stylesheet" type="text/css" href="../css/materialize.css">
  
  <title>Editar Post</title>

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
    <?php //include_once('../header.php'); ?>
  <main>
    <div class="section"></div>
    <center class="container" style="margin-left: 25%">
      <form class="col s12" action="" method="post" enctype="multipart/form-data">
        <div class="file-field input-field">
          <div class="btn">
            <span>Imagem</span>
            <input type="file" accept="image/*" name="ImageFile" id="ImageFile">
          </div>
          <div class="file-path-wrapper">
            <input class="file-path validate" type="text">
          </div>
        </div>

        <div class="row">
          <div class="row">
            <div class="input-field col s12">
              <textarea name="articlename" id="articlename" class="materialize-textarea validate"><?php if(isset($data['post_name'])){ echo $data['post_name']; }?></textarea>
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
              <textarea name="articlebody" id="articlebody" class="materialize-textarea"><?php if(isset($data['post_content'])){ echo $data['post_content']; }?></textarea>
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
  <script src="../js/materialize.js"></script>
  <script src="ckeditor/ckeditor.js"></script>
  <?php global $success; if($success){echo "<script>document.addEventListener('DOMContentLoaded', function() { M.toast({html: '$mensagem'});});</script>";} ?>
  <?php global $gb_err; if($gb_err){echo "<script>document.addEventListener('DOMContentLoaded', function() { M.toast({html: '$mensagem'});});</script>";} ?>
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
        format: 'dd-mm-yyyy',
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
      // materialize probaby hate m
      var elems = document.querySelectorAll('.datepicker');
      var instances = M.Datepicker.init(elems, datetimeopt);
    });

    M.updateTextFields();

    CKEDITOR.replace('articlebody');
  </script>
</body>
</html>