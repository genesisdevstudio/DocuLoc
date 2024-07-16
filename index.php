<?php
    include($_SERVER['DOCUMENT_ROOT'].'/lib/config.php');
    include($_SERVER['DOCUMENT_ROOT'].'/lib/conn.php');
    include($_SERVER['DOCUMENT_ROOT'].'/lib/enviar-email.php');

    $name_page = ucfirst("login");

    if((isset($_COOKIE['user']) && $_COOKIE['user'] != '') && (isset($_COOKIE['token']) && $_COOKIE['token'] != '') && (isset($_COOKIE['access']) && $_COOKIE['access'] != '')) {
        header('location: pages/home.php');
        exit();
    }

    if(isset($_REQUEST['entrar'])) {
        $login = addslashes($_REQUEST['login']);
        $senha = addslashes($_REQUEST['password']);

        if ($login == '' || $senha == '') {
            $erro = 1;
            $msg_error = 'Todos os campos são obrigatórios.';
        } else {
            $login_crypt = md5($login);
            $senha_crypt = md5($senha);
            $status = 1; // 2 - aguardando aprovaçãol | 1 - ativo | 0 - inativo

            $sql = "SELECT * FROM {$table_prefix}users WHERE user_login=? AND user_password=? AND user_status=? LIMIT 1";
            $statement = $pdo->prepare($sql);
            $statement->bindValue(1, $login_crypt);
            $statement->bindValue(2, $senha_crypt);
            $statement->bindValue(3, $status);

            if ($statement->execute() === false) {
                $erro = 1;
                $msg_error = 'Erro ao checar os dados, tente mais tarde.';
            } else {
                if ($statement->rowCount() < 1) {
                    $erro = 1;
                    $msg_error = 'Erro ao validar o login.';
                } else {
                    $success = $statement->fetchAll(PDO::FETCH_ASSOC);

                    setcookie('user', "{$success[0]['user_name']}", time() + 3600, '/');
                    setcookie('token', "{$success[0]['user_token']}", time() + 3600, '/');
                    setcookie('access', "{$success[0]['type_of_user']}", time() + 3600, '/');

                    header('location: pages/home.php');
                    exit();
                }
            }
        }
    }

    if (isset($_REQUEST['recuperar'])) {
        $email_form = addslashes($_REQUEST['email_recuperar']);
        $email_crypt = md5($email_form);
        $status = 1;

        $sql = "SELECT user_name FROM {$table_prefix}users WHERE user_email=? AND user_login=? AND user_status=? LIMIT 1";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(1, $email_form);
        $statement->bindValue(2, $email_crypt);
        $statement->bindValue(3, $status);

        if ($statement->execute() === false) {
            $erro = 1;
            $msg_error = 'Erro ao checar os dados, tente mais tarde.';
        } else {
            if ($statement->rowCount() < 1) {
                $erro = 1;
                $msg_error = 'Erro ao validar o email informado.';
            } else {
                $success = $statement->fetchAll(PDO::FETCH_ASSOC);
                $nome_email = $success[0]['user_name'];
                $senha_provisoria = substr(md5($email_form.$nome_email.date('YmdHis')), 0, 8);

                $dados_email = array(
                    "sender_email" => "site@doculoc.com.br",
                    "sender_name" => "DocuLoc - Redefinição de senha",
                    "sender_pass" => "Docu@2024@loc",
                    "to_email" => $email_form,
                    "to_name" => $nome_email,
                    "subject" => "Redefinição de Senha - DocuLoc",
                    "message" => "<p>Olá, {$nome_email}.<br><br>Acesse o painel com a senha provisória abaixo.<br>Senha provisória: <b>{$senha_provisoria}</b><br><br>Vá em <mark>perfil</mark> para alterá-la para uma senha definitiva.<br><br><br>Atenciosamente,<br>Suporte do sistema DocuLoc</p>",
                    "cc_email" => null,
                    "cc_name" => null,
                    "attachment" => null,
                    "attachment_name" => null
                );

                $recuperar_conta = disparaEmail($dados_email);

                if (is_array($recuperar_conta)) {
                    $erro = 1;
                } else {
                    $sql = "UPDATE {$table_prefix}users SET user_password=? WHERE user_email=? AND user_login=?";
                    $statement = $pdo->prepare($sql);
                    $statement->bindValue(1, md5($senha_provisoria));
                    $statement->bindValue(2, $email_form);
                    $statement->bindValue(3, $email_crypt);

                    if ($statement->execute() !== false) {
                        $sucesso = 1;
                        $msg_sucesso = "Em breve receberá um e-mail de recuperação de conta no email informado!";
                    }
                }
            }
        }
    }
?>

<?php require_once(PATHURL.'lib/include/head.php'); ?>
    <body>
        <main class="main-content mt-0">
            <section>
                <div class="page-header min-vh-100">
                    <div class="container">
                        <div class="row">
                            <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column mx-lg-0 mx-auto">
                                <div class="card card-plain">
                                    <div class="card-header pb-0 text-start">
                                        <?php require_once (PATHURL.'/pages/components/login_alerts.php'); ?>

                                        <h4 class="font-weight-bolder">Login</h4>
                                        <p class="mb-0">Insira seu email e senha para acessar o sistema</p>
                                    </div>

                                    <div class="card-body">
                                        <form role="form" action="<?=$_SERVER['PHP_SELF'];?>" method="POST">
                                            <div class="mb-3">
                                                <input type="email" name="login" class="form-control form-control-lg" placeholder="Email" aria-label="Email" required>
                                            </div>

                                            <div class="mb-3">
                                                <input type="password" name="password" class="form-control form-control-lg" placeholder="Senha" aria-label="Password" required>
                                            </div>

                                            <div class="text-center">
                                                <input type="submit" value="Entrar" name="entrar" class="btn btn-lg btn-primary btn-lg w-100 mt-4 mb-0">
                                            </div>
                                        </form>
                                    </div>

                                    <div class="card-footer text-center pt-0 px-lg-2 px-1">
                                        <!-- <p class="text-sm mx-auto">
                                            Não tem uma conta?
                                            <a href="javascript:;" class="text-primary text-gradient font-weight-bold">Cadastre-se</a>
                                        </p> -->
                                        <a class="text-primary text-gradient text-sm font-weight-bold pointer-cursor" data-bs-toggle="modal" data-bs-target="#passRecover">Esqueci minha senha</a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 end-0 text-center justify-content-center flex-column">
                                <div class="position-relative bg-gradient-primary h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center overflow-hidden" style="background-image: url('<?=PATHURL;?>assets/img/background/image_on_login.jpg'); background-size: cover; background-position: center; opacity: 0.7;">
                                    <span class="mask opacity-6"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <!-- Modal Pass Recover -->
        <div class="modal fade" id="passRecover" tabindex="-1" role="dialog" aria-labelledby="passRecoverLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="passRecoverLabel">Recuperar senha</h5>

                        <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">
                                <i class="ph-bold ph-x"></i>
                            </span>
                        </button>
                    </div>

                    <form action="<?=$_SERVER['PHP_SELF'];?>" method="POST">
                        <div class="modal-body">
                            <div class="row">
                                <div class="form-group">
                                    <label for="email-login">E-mail cadastrado</label>
                                    <input type="email" name="email_recuperar" class="form-control" id="email-login" placeholder="Digite aqui..." required autocomplete="off">
                                </div>
                            </div>
                        </div>
                        
                        <div class="modal-footer">
                            <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Fechar</button>
                            <input type="submit" name="recuperar" value="Recuperar senha" class="btn btn-success">
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?php require_once(PATHURL.'lib/include/footer_scripts.php'); ?>
    </body>
</html>