<?php

if (isset($_GET['page'])) 
  $page = $_GET['page']; 
else 
  $page = "none.jpg";

$file = "http://localhost/blog/image/" . $page;

?>
<title>Path Traversal</title>

<div class="container" style="margin-top: 50px">
  <img src='<?php
    echo $file;
  ?>'>
</div>