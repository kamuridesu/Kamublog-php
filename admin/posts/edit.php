<?php
  session_start();
	if(!(isset($_SESSION['email']))){
    header("Location: ../login.php");
  }

  // global Bariables
  $success = false;
  $gb_err = false;
  $mensagem;

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

  function slugify($post_name){
    // kono bullshit here
		$exclude = array(" ", "?", "/", "~", "=", "´", "`", ".", ",", "^", "[", "]", "{", "}", "(", ")", "ª", "º", "°", "§", "+", "_", "@", "!", "#", "$", "%", "¨", "*", "\\", "|", "'", '"', ":", ";");
		foreach($exclude as $exc) {
			$post_name = str_replace($exc, "-", $post_name);
		}
		return $post_name;
	}

  function println(){
    $args = func_get_args();
    foreach($args as $arg){
      echo "<p>" . $arg . "</p>";
    }
  }

  function save_img(){
    if(!(empty($_FILES['ImageFile']['name']))){
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
    } else {
      return false;
    }
    
  }


  function save($draft){
    $name = $_POST['articlename'];
    $author = $_POST['author'];
    $date = format_date($_POST['date']);
    $content = $_POST['articlebody'];
    $slug = slugify($_POST['articlename']);
    $image = save_img();
    if($image === false){
      $image = null;
    }

    println($name);
    println($author);
    println($date);
    println($content);
    println($slug);
    println($draft);
    println($image);
    
  }

  function update_post(){
    $conn = new mysqli("127.0.0.1", "root", "", "test");
    if($conn->connect_error){
      die("A conexão com o banco de dados falhou: " . $conn->connect_error);
    }

    //$sql = "UPDATE posts SET "

    $conn->close();
  }

  function retrieve_data($slug){
    $conn = new mysqli("127.0.0.1","root", "","test");
    if($conn->connect_error){
      die("A conexão com o banco de dados falhou : " . $conn->connect_error);
    }

    $sql = "SELECT post_id, post_name, post_date, post_author, post_content, published, post_slug, post_image FROM posts";
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

  if($_SERVER["REQUEST_METHOD"] == "POST"){
    save($data['published']);
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
    <img src="/Kamublog/admin/functions/getImage.php?file=<?php if(isset($data['post_image'])){ echo basename($data['post_image']); } ?>" height="70%" width="70%">
      <form class="col s12" action="" method="post" enctype="multipart/form-data">
        <div class="file-field input-field">
          <div class="btn">
            <span>Imagem</span>
            <input type="file" accept="image/*" name="ImageFile" id="ImageFile">
          </div>
          <div class="file-path-wrapper">
            <input placeholder="<?php if(isset($data['post_image'])) { echo basename($data['post_image']);} ?>" class="file-path validate" type="text">
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
      var instance = M.Datepicker.getInstance(elems[0]);
      instance.setDate("<?php if(isset($unpacked_date)){ echo $unpacked_date; }?>");
      elems[0].value = instance.toString(); // materialze is just broken so we need to put the value of the element as the string of the instance;
      // of cours this is a bad solution as it breaks the lable but.......
    });

    M.updateTextFields();

    CKEDITOR.replace('articlebody');
  </script>
</body>
</html>