<?php
session_start();
session_destroy();
require_once "../utils.php";
redirect("index.php");