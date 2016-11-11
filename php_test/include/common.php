<?php
session_start();
if ( $_SESSION['page_url'] != $_SERVER['REQUEST_URI'] ) {
    $_SESSION['prev_page_id'] = $_SESSION['page_id'];
    $_SESSION['prev_page_url'] = $_SESSION['page_url'];

    $_SESSION['page_url'] = $_SERVER['REQUEST_URI'];
}
?>