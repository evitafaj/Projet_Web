<?php
function loginForm(){
    if (file_exists("connection.html") && filesize("connection.html")>0){
        $contents = file_get_contents("connection.html");
        echo $contents;
    }
}
if (!isset($_SESSION["pseudo"])){
    loginForm();
}
?>