<?php
  include($_SERVER['DOCUMENT_ROOT'].'/lib/config.php');
  session_start();

  $name_page = ucfirst("success");

  $code = $_REQUEST['code'];
  $message = $_REQUEST['msg'];
?>  
  <?php require_once('.'.PATHURL.'lib/include/head-pages.php'); ?>

  <body class="g-sidenav-show bg-gray-100">
    <main class="main-content position-relative border-radius-lg">
      <!-- Content -->
      <?php require_once('.'.PATHURL.'pages/success/'.$code.'.php'); ?>
      <!-- End Content -->
    </main>
  </body>
</html>