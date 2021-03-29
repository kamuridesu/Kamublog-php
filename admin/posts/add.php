<?php
  session_start();
	if(!(isset($_SESSION['email']))){
    header("Location: ../login.php");
  }

  // global Bariables
  $success = false;
  $gb_err = false;
  $mensagem;

  function save_img(){
    $target = "../uploads/";
    $filename = basename($_FILES['ImageFile']['name']);
    $ext = explode(".", $filename);
    $ext = "." . $ext[sizeof($ext) - 1];
    $filename = explode($ext, $filename)[0] . time() . $ext;
    $targ_file = $target . $filename;
    $check = getimagesize($_FILES['ImageFile']["tmp_name"]);
    if($check !== false){
        if(move_uploaded_file($_FILES["ImageFile"]['tmp_name'], $targ_file)){
          return $targ_file;
        } else {
          return false;
        }
    } else {
      return false;
    }
  }

  function slugify($post_name){
    // kono bullshit here
		$exclude = array(" ", "?", "/", "~", "=", "´", "`", ".", ",", "^", "[", "]", "{", "}", "(", ")", "ª", "º", "°", "§", "+", "_", "@", "!", "#", "$", "%", "¨", "*", "\\", "|", "'", '"', ":", ";");
		foreach($exclude as $exc) {
			$post_name = str_replace($exc, "-", $post_name);
		}
		return $post_name;
	}

  function format_date($date){
    // materiailze donnut like me
		$arr = (explode(" ", $date));
		$day = explode(",", $arr[1])[0];
		$year = $arr[2];
		$month = 0;
		$months = array('Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dec');
		$i = 0;
		foreach($months as $mon) {
			$i++;
			if ($mon === $arr[0]){
				$month = $i;
			}
		}
    return ($year . "-" . $month . "-" . $day);
	}

  function save_to_db($name, $author, $date, $content, $draft, $image, $slug){
    $conn = new mysqli("127.0.0.1", "root", "", "test");
    if($conn->connect_error){
      die("A conexão com o banco de dados falhou: " . $conn->$connect_error);
    }
    $sql = "INSERT INTO posts (`post_name`, `post_date`, `post_author`, `post_content`, `published`, `post_slug`, `post_image`) VALUES ('$name', '$date', '$author', '$content', '$draft', '$slug', '$image')";
    if ($conn->query($sql) === true){
      // ???????????????????
      global $success;
      $success = true;
      if ($draft === 0) {
        global $mensagem;
        $mensagem = "Post publicado com sucesso!";
      } else {
        global $mensagem;
        $mensagem = "Post salvo com sucesso!";
      }
    }

  }

  function save(){
    $name = $_POST['articlename'];
    $author = $_POST['author'];
    $date = format_date($_POST['date']);
    $content = $_POST['articlebody'];
    $slug = slugify($_POST['articlename']);
    $draft = 0;
    if(isset($_POST['save'])){
      $draft = 1;
    }
    $image = save_img();
    if($image === false){
      $image = null;
    }
    save_to_db($name, $author, $date, $content, $draft, $image, $slug);
  }

  function initialize(){
    if (isset($_POST)){
      $required = array("articlename", "date", "author", "articlebody");
      $err = false;
      foreach($required as $field){
        if(empty($_POST[$field])){
          $err = true;
        }
      }
      if($err){
        global $gb_err;
        global $mensagem;
        $gb_err = true;
        $mensagem = "Erro! Verifique se os campos estão preenchidos corretamente!";
      } else {
        save();
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
    <?php include_once('../header.php'); ?>
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
      var elems = document.querySelectorAll('.datepicker');
      var instances = M.Datepicker.init(elems, datetimeopt);
    });

    M.updateTextFields();

    CKEDITOR.replace('articlebody');
  </script>
</body>
</html>