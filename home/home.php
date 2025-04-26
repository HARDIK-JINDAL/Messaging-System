<?php

include("home.html");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST["SIGNUP"])) {
        header("Location: /DBMS_PROJECT/signup/signup.html");
        exit;
    }
    if (isset($_POST["LOGIN"])) {
        header("Location: /DBMS_PROJECT/login/login.html");
        exit;
    }
    if (isset($_POST["DELETE"])) {
        header("Location: /DBMS_PROJECT/delete-acc/delete.html");
        exit;
    }
}
?>
