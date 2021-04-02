<?php
session_start();
if(!isset($_SESSION['email'])){
  header("Location: ../index.php");
}

function delete_entry($post_id){
  $conn = new mysqli("127.0.0.1", "root", "", "test");
  if($conn->connect_error){
    die("A conexão falhou com o banco de dados: " . $conn->connect_error);
  }
  $sql = "DELETE FROM posts WHERE post_id = '$post_id'";
  if($conn->query($sql) === true){
    header("Location: ./posts.php?success=true");
  } else {
    header("Location: ./posts.php?success=false");
  }

}

function initialize(){
  if(isset($_GET['post_id'])){
    delete_entry($_GET['post_id']);
  } else {
    header("Location: ../index.php");
  }
}

initialize();