<?php
  session_start();

  try{
    $conn = new mysqli("127.0.0.1","root", "","test");
    if($conn->connect_error){
      die("A conexão com o banco de dados falhou falhou: " . $conn->connect_error);
    }

    $sql_query = "SELECT COUNT(*) FROM posts";
    $total = $conn->query($sql_query)->fetch_row()[0];

    $limit = 5;

    $pages = ceil($total / $limit);

    $page = min($pages, filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, array(
      'options' => array(
        'default'   => 1,
        'min_range' => 1,
      ),
    )));

    $offset = ($page - 1)  * $limit;

    $start = $offset + 1;
    $end = min(($offset + $limit), $total);

    $prevlink = ($page > 1) ? '<a href="?page=1" title="Primeira página">&laquo;</a> <a href="?page=' . ($page - 1) . '" title="Página anterior">&lsaquo;</a>' : '<span class="disabled">&laquo;</span> <span class="disabled">&lsaquo;</span>';

    $nextlink = ($page < $pages) ? '<a href="?page=' . ($page + 1) . '" title="Próxima página">&rsaquo;</a> <a href="?page=' . $pages . '" title="Última página">&raquo;</a>' : '<span class="disabled">&rsaquo;</span> <span class="disabled">&raquo;</span>';
    
    $sql_query = "SELECT * FROM posts ORDER BY post_id LIMIT $limit OFFSET $offset";
    $stmt = $conn->query($sql_query);
    

  } catch (Exception $e) {
    echo '<p>', $e->getMessage(), '</p>';
  }
  
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="../css/materialize.css">
  <title>Editar posts</title>

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

    .main-body{
      margin-left: 15%;
    }

  </style>
</head>
<body>
  <?php include_once("../header.php"); ?>

  <main class="container">
    <div class="main-body">
      <?php
        function output($name, $content){
          $middle = "";
          if (strlen($content) <= 50){
            $middle = $content;
          } else {
            $content = $content;
            $content = substr($content, 0, 50);
            $content = $content."...";
            $middle = $content;
          }
          echo '<p>', $name, '<span>', $middle,  '</span> <span class="right">Delete</span><span class="right" style="margin-right: 3%">Edit</span></p>';
        }
        if (!($stmt === false)){
          if ($stmt->num_rows > 0) {
            
            while ($row = $stmt->fetch_assoc()){
              output($row['post_name'], $row['post_content']);
            }
          } else {
            echo '<p>No results could be displayed.</p>';
          }
        } else {
          print_r($conn->error_list);
        }
      ?>
    </div>
    <center>
      <ul class="pagination">
        <li<?php echo '<div id="paging"><p>', $prevlink, ' Page ', $page, ' of ', $pages, ' pages, displaying ', $start, '-', $end, ' of ', $total, ' results ', $nextlink, ' </p></div>';?></li>
      </ul>
    </center>
  </main>

  <script src="../js/materialize.js"></script>
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