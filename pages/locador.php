<?php
  include($_SERVER['DOCUMENT_ROOT'].'/lib/config.php');
  include($_SERVER['DOCUMENT_ROOT'].'/lib/conn.php');
  include($_SERVER['DOCUMENT_ROOT'].'/lib/global_functions.php');
  session_start();

  $name_page = ucfirst("Form Locador");

  $token_case = addslashes($_REQUEST['case']);

  if (empty($token_case)) {
    $_SESSION['error_code'] = 403;
    $_SESSION['error_message'] = 'Você não tem permissão de acesso a este conteúdo.';

    header('location: error.php');
    exit();
  } else {
    $case_data = getCaseByTokenCase($token_case);

    if ($case_data['code'] != 200) {
      $_SESSION['error_code'] = $case_data['code'];
      $_SESSION['error_message'] = $case_data['message'];

      header('location: error.php');
      exit();
    }   
  }
?>  
  <?php require_once('.'.PATHURL.'lib/include/head-pages.php'); ?>

  <body class="g-sidenav-show bg-gray-100">
    <main class="main-content position-relative border-radius-lg">
      <!-- Content -->
      <?php require_once('.'.PATHURL.'pages/components/locador/locador_content.php'); ?>      
      <!-- End Content -->

      <?php require_once('.'.PATHURL.'pages/components/footer.php'); ?>
    </main>

    <?php require_once('.'.PATHURL.'lib/include/footer_scripts-externalforms.php'); ?>
    <?php require_once('.'.PATHURL.'lib/include/footer_scripts-locador.php'); ?>
  </body>
</html>