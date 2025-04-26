<?php

include("home.html");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST["SIGNUP"])) {
        header("Location: ../signup/signup.html");
        exit;
    }
    if (isset($_POST["LOGIN"])) {
        header("Location: ../login/login.html");
        exit;
    }
    if (isset($_POST["DELETE"])) {
        header("Location: ../delete-acc/delete.html");
        exit;
    }
}
?>
