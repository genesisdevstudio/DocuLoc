<?php
    include('../lib/config.php');
    include('../lib/conn.php');
    include('../lib/global_functions.php');

    if ($_REQUEST['save_locador']) {
        $received_form = $_REQUEST;
        $token_case = $_REQUEST['token_case'];
        $received_files = salvarArquivosCarregados($_FILES, "../docs/cases/{$token_case}/", $token_case);
        unset($received_form['save_locador']);

        echo '<pre>' . print_r($received_files, true) . '</pre><hr>';
        echo '<pre>' . print_r($received_form, true) . '</pre><hr>';

        foreach ($received_files as $name_doc => $contents) {
            if (!empty($contents['url_final'])) {
                $filePath = str_replace(WEBURL, '..', $contents['url_final']);
                uploadDocumentToCloudinary($filePath, $token_case, $name_doc, 'locador');
            }
        }

        foreach ($received_form as $key => $value) {
            if (strstr($key, 'info_pessoa_')) {
                $new_key = str_replace('info_pessoa_', '', $key);
                $info_pessoa[$new_key] = $value;
            } else if (strstr($key, 'contato_')) {
                $new_key = str_replace('contato_', '', $key);
                $contato[$new_key] = $value;
            } else if (strstr($key, 'dados_bancarios_')) {
                $new_key = str_replace('dados_bancarios_', '', $key);
                $dados_bancarios[$new_key] = $value;
            } else if (strstr($key, 'dados_imovel_')) {
                $new_key = str_replace('dados_imovel_', '', $key);
                $dados_imovel[$new_key] = $value;
            }            
        }

        $token_pessoa = md5($info_pessoa['cpf'] . $contato['email'] . date('Y-m-d H:i:s'));
        $info_pessoa['tipo'] = 'locador';
        $info_pessoa['token'] = $token_pessoa;
        $contato['token_pessoa'] = $token_pessoa;
        $dados_bancarios['token_pessoa'] = $token_pessoa;
        $dados_imovel['token_pessoa'] = $token_pessoa;

        echo '<pre>' . print_r($info_pessoa, true) . '</pre><hr>';
        echo '<pre>'. print_r($contato, true). '</pre><hr>';
        echo '<pre>'. print_r($dados_bancarios, true). '</pre><hr>';
        echo '<pre>'. print_r($dados_imovel, true). '</pre>';
        
        exit();
        // $received_form['token_case'] = substr(md5(date('Y-m-d H:i:s')), 0, 8);
        // $received_form['creator'] = $_COOKIE['token'];

        // if (in_array($received_form['tipo_garantia'], $need_attach)) {
        //     $received_form['status'] = 'waiting_attach';
        // }
        
        // $table_name = 'cases';
        // $save_request = saveOnDB($received_form, $table_name);

        // if (is_array($save_request)) {
        //     $code = $save_request['code'];
        //     $message = $save_request['message'];

        //     header("location: ../pages/cases.php?code={$code}&msg={$message}");
        //     exit();
        // }
    }
    