<?php
    include('../lib/config.php');
    include('../lib/conn.php');
    include('../lib/global_functions.php');

    if ($_REQUEST['save_case']) {
        $need_attach = array('Caução', 'Fiador', 'Seguro Fiança', 'Título de capitação', 'Fiança Credpago', 'Fiança Velo', 'Fiança Doculoc', 'Fiança Outros');
        $received_form = $_REQUEST;
        unset($received_form['save_case']);
        
        $received_form['token_case'] = substr(md5(date('Y-m-d H:i:s')), 0, 8);
        $received_form['creator'] = $_COOKIE['token'];

        if (in_array($received_form['tipo_garantia'], $need_attach)) {
            $received_form['status'] = 'waiting_attach';
        }
        
        $table_name = 'cases';
        $save_request = saveOnDB($received_form, $table_name);

        if (is_array($save_request)) {
            $code = $save_request['code'];
            $message = $save_request['message'];

            if ($code == 201) {
                $token_case = $received_form['token_case'];
                $url_locador = WEBURL . '/pages/locador.php?case=' . $token_case;
                $url_locatario = WEBURL . '/pages/locatario.php?case=' . $token_case;

                $qr_code_locador = generateAndUploadQRCode($url_locador, $token_case, 'locador');
                $qr_code_locatario = generateAndUploadQRCode($url_locatario, $token_case, 'locatario');
                
                $message .= " Código do caso é {$token_case}";
                header("location: ../pages/cases.php?code={$code}&msg={$message}");
                exit();
            } else {
                header("location: ../pages/cases.php?code={$code}&msg={$message}");
                exit();
            }
        }
    }

    if ($_REQUEST['delete_case']) {
        $table_name = 'cases';

        $case = $_REQUEST['token_case'];
        $delete_request = deleteOnDB($case, $table_name);

        if (is_array($delete_request)) {
            $code = $delete_request['code'];
            $message = $delete_request['message'];

            header("location: ../pages/cases.php?code={$code}&msg={$message}");
            exit();
        }
    }
    