<?php
    include('../lib/config.php');
    include('../lib/conn.php');
    include('../lib/global_functions.php');

    if ($_REQUEST['save_locador']) {
        $received_form = $_REQUEST;
        $token_case = $_REQUEST['token_case'];
        $received_files = salvarArquivosCarregados($_FILES, "../docs/cases/{$token_case}/", $token_case);
        unset($received_form['save_locador']);

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
        $info_pessoa['tipo'] = '1';
        $info_pessoa['token'] = $token_pessoa;
        $contato['token_pessoa'] = $token_pessoa;
        $dados_bancarios['token_pessoa'] = $token_pessoa;
        $dados_imovel['proprietario'] = $token_pessoa;
        
        $table_name = 'info_pessoa';
        $save_request = saveOnDB($info_pessoa, $table_name);

        if (is_array($save_request) && $save_request['code'] != 201) {
            $code = $save_request['code'];
            $message = $save_request['message'];

            header("location: ../pages/locador.php?code={$code}&msg={$message}");
            exit();
        } else {
            $table_name = 'contato';
            $save_request = saveOnDB($contato, $table_name);
    
            if (is_array($save_request) && $save_request['code'] != 201) {
                $code = $save_request['code'];
                $message = $save_request['message'];
    
                header("location: ../pages/locador.php?code={$code}&msg={$message}");
                exit();
            } else {
                $table_name = 'dados_bancarios';
                $save_request = saveOnDB($dados_bancarios, $table_name);
        
                if (is_array($save_request) && $save_request['code'] != 201) {
                    $code = $save_request['code'];
                    $message = $save_request['message'];
        
                    header("location: ../pages/locador.php?code={$code}&msg={$message}");
                    exit();
                } else {
                    $table_name = 'dados_imovel';
                    $save_request = saveOnDB($dados_imovel, $table_name);
            
                    if (is_array($save_request) && $save_request['code'] != 201) {
                        $code = $save_request['code'];
                        $message = $save_request['message'];
            
                        header("location: ../pages/locador.php?code={$code}&msg={$message}");
                        exit();
                    } else {
                        $message = "Seus dados foram salvos com sucesso.<br>Daremos continuidade no seu caso.";

                        header("location: ../pages/success.php?code={$code}&msg={$message}");
                        exit();
                    }
                }
            }
        }
    }
    