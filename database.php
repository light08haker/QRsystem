<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "attendance");

if(!$con) {
    die("Cannot connect to the database");
}
?>