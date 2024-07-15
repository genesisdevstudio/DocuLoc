<?php    
    require_once '../vendor/autoload.php';

    use \PhpOffice\PhpWord\TemplateProcessor;
    use Endroid\QrCode\Builder\Builder;
    use Endroid\QrCode\Encoding\Encoding;
    use Endroid\QrCode\ErrorCorrectionLevel;
    use Endroid\QrCode\Writer\PngWriter;
    use Cloudinary\Cloudinary;
    use Cloudinary\Configuration\Configuration;
    use Cloudinary\Api\Upload\UploadApi;
    use Cloudinary\Transformation\Resize;

    function desconectar() {
        setcookie('user', '', time() - 3600, '/');
        setcookie('token', '', time() - 3600, '/');
        setcookie('access', '', time() - 3600, '/');

        session_start();
        session_destroy();

        header('location: .'.PATHURL);
        exit();
    }

    function validate() {
        if ($_COOKIE['token'] == '' || empty($_COOKIE['token']) || !isset($_COOKIE['token'])) {
            header('location: .'.PATHURL);
            exit();
        }
    }

    function saveOnDB($datas = array(), $table_name=null) 
    {
        global $pdo;
        global $table_prefix;

        $columns = '';
        $values_bind = '';
        $count_values = 1;
        $values_unbinded = array();
        $last_key = array_key_last($datas);
        foreach ($datas as $colum => $data) {
            if ($last_key != $colum) {
                $columns .= "{$colum},";
                $values_bind .= '?,';
            } else {
                $columns .= "{$colum}";
                $values_bind .= '?';
            }

            $values_unbinded[$count_values] = $data;
            $count_values += 1;
        }

        $sql = "INSERT INTO {$table_prefix}{$table_name} ({$columns}) VALUES ({$values_bind})";
        $statement = $pdo->prepare($sql);
        foreach ($values_unbinded as $position => $value) {
            $statement->bindValue($position, $value);
        }

        if ($statement->execute() === false) {
            $return_data = array(
                'code' => 500,
                'message' => 'Erro ao checar os dados, tente mais tarde.'
            );
        } else {
            $return_data = array(
                'code' => 201,
                'message' => 'Criado com sucesso!'
            );
        }

        return $return_data;
    }

    function updateOnDB($datas = array(), $table_name=null, $where_params = array())
    {
        global $pdo;
        global $table_prefix;

        $update_list = '';
        $count_values = 1;
        $values_unbinded = array();
        $last_key = array_key_last($datas);
        foreach ($datas as $colum => $data) {
            if ($last_key != $colum) {
                $update_list .= "{$colum}=?,";
            } else {
                $update_list .= "{$colum}=?";
            }

            $values_unbinded[$count_values] = $data;
            $count_values += 1;
        }
        $count_values += 1;

        $column_where = $where_params['column_name'];
        $value_where = $where_params['value'];

        $sql = "UPDATE {$table_prefix}{$table_name} SET {$update_list} WHERE {$column_where}=?";
        $statement = $pdo->prepare($sql);
        foreach ($values_unbinded as $position => $value) {
            $statement->bindValue($position, $value);
        }
        $statement->bindValue($count_values, $value_where);

        if ($statement->execute() === false) {
            $return_data = array(
                'code' => 500,
                'message' => 'Erro ao atualizar os dados, tente mais tarde.'
            );
        } else {
            $return_data = array(
                'code' => 200,
                'message' => "Caso atualizado com sucesso!"
            );
        }

        return $return_data;
    }

    function deleteOnDB($token_case=null, $table_name=null)
    {
        global $pdo;
        global $table_prefix;

        $sql = "DELETE FROM {$table_prefix}{$table_name} WHERE token_case=?";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(1, $token_case);

        if ($statement->execute() === false) {
            $return_data = array(
                'code' => 500,
                'message' => 'Erro ao deletar caso, tente mais tarde.'
            );
        } else {
            $return_data = array(
                'code' => 200,
                'message' => "Caso deletado com sucesso!"
            );
        }

        return $return_data;
    }

    function getCases($type_of_user=null, $team_tokens=null)
    {
        global $pdo;
        global $table_prefix;

        if ($type_of_user == 1) {
            $sql = "SELECT * FROM {$table_prefix}cases";
            $statement = $pdo->prepare($sql);
        } else if ($type_of_user == 2) {
            $sql = "SELECT * FROM {$table_prefix}cases WHERE creator IN (?)";
            $statement = $pdo->prepare($sql);
            $statement->bindValue(1, $team_tokens);
        } else {
            $sql = "SELECT * FROM {$table_prefix}cases WHERE creator=?";
            $statement = $pdo->prepare($sql);
            $statement->bindValue(1, $_COOKIE['token']);
        }

        // Executa a query
        if ($statement->execute() === false) {
            $return_data = array(
                'code' => 500,
                'message' => 'Erro ao buscar casos, tente mais tarde.'
            );
        } else {
            $return_data = $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        return $return_data;
    }
    
    function getCaseByTokenCase($token_case=null)
    {
        global $pdo;
        global $table_prefix;
                
        $sql = "SELECT * FROM {$table_prefix}cases WHERE token_case=?";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(1, $token_case);
        

        // Executa a query
        if ($statement->execute() === false) {
            $return_data = array(
                'code' => 500,
                'message' => 'Erro ao buscar caso, tente mais tarde'
            );            
        } else {
            if ($statement->rowCount() < 1) {
                $return_data = array(
                    'code' => 404,
                    'message' => 'Caso não encontrado'
                );
            } else {
                $return_data = $statement->fetchAll(PDO::FETCH_ASSOC);
                $return_data['code'] = 200;
            }
        }

        return $return_data;
    }

    function getUser($user_token=null)
    {
        global $pdo;
        global $table_prefix;

        $sql = "SELECT * FROM {$table_prefix}users WHERE user_token=?";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(1, $user_token);

        if ($statement->execute() === false) {
            $return_data = array(
                'code' => 500,
                'message' => 'Erro ao buscar usuário, tente mais tarde.'
            );
        } else {
            $return_data = $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        return $return_data;
    }

    function getProfile($user_token=null)
    {
        global $pdo;
        global $table_prefix;

        $sql = "SELECT * FROM {$table_prefix}profile WHERE user_token=?";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(1, $user_token);

        if ($statement->execute() === false) {
            $return_data = array(
                'code' => 500,
                'message' => 'Erro ao buscar perfil, tente mais tarde.'
            );
        } else {
            $return_data = $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        return $return_data;
    }

    function getLevel($type_of_user=null)
    {
        global $pdo;
        global $table_prefix;

        $sql = "SELECT * FROM {$table_prefix}users_types WHERE id=?";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(1, $type_of_user);

        if ($statement->execute() === false) {
            $return_data = array(
                'code' => 500,
                'message' => 'Erro ao buscar cargo, tente mais tarde.'
            );
        } else {
            $return_data = $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        return $return_data;
    }

    function getEmployees($filter_data=null)
    {
        global $pdo;
        global $table_prefix;

        // $sql = "SELECT * FROM {$table_prefix}users WHERE {$filters}";
    }

    function generateContract($case=null)
    {
      $case_data = getCaseByTokenCase($case);

      $templateProcessor = new TemplateProcessor('../assets/docs/contrato-aluguel.docx');
      $templateProcessor->setValues(array('nome_locador' => $case_data[0]['locador'], 'cpf_locador' => $case_data[0]['cpf_locador']));
      $templateProcessor->setValues(array('nome_locatario' => $case_data[0]['locatario'], 'cpf_locatario' => $case_data[0]['cpf_locatario']));

      $pathToSave = "../docs/cases/{$case}/contrato.docx";
      $templateProcessor->saveAs($pathToSave);

      // Força o download
      // faz o teste se o arquivo realmente existe
      if(isset($pathToSave) && file_exists($pathToSave)){
          // verifica a extensão do arquivo para pegar o tipo
          switch(strtolower(substr(strrchr(basename($pathToSave),"."),1))){
              case "pdf": $tipo="application/pdf"; break;
              case "exe": $tipo="application/octet-stream"; break;
              case "zip": $tipo="application/zip"; break;
              case "doc": $tipo="application/msword"; break;
              case "xls": $tipo="application/vnd.ms-excel"; break;
              case "ppt": $tipo="application/vnd.ms-powerpoint"; break;
              case "gif": $tipo="image/gif"; break;
              case "png": $tipo="image/png"; break;
              case "jpg": $tipo="image/jpg"; break;
              case "mp3": $tipo="audio/mpeg"; break;
              case "php": // deixar vazio por segurança
              case "htm": // deixar vazio por segurança
              case "html": // deixar vazio por segurança
          }

          header("Content-Type: ".$tipo); // informa o tipo do arquivo ao navegador
          header("Content-Length: ".filesize($pathToSave)); // informa o tamanho do arquivo ao navegador
          header("Content-Disposition: attachment; filename=".basename($pathToSave)); // informa ao navegador que é tipo anexo e faz abrir a janela de download, tambem informa o nome do arquivo

          readfile($pathToSave); // lê o arquivo
      }
    }

    function generateQRCode($url=null, $case=null, $type=null)
    {
        // Verifica se a URL é válida
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return 'URL inválida!';
        }

        // Cria um novo QR Code
        $result = Builder::create()
            ->writer(new PngWriter())
            ->writerOptions([])
            ->data($url)
            ->encoding(new Encoding('UTF-8'))
            // ->errorCorrectionLevel(new ErrorCorrectionLevel(ErrorCorrectionLevel::High))
            ->size(300)
            ->margin(10)
            ->build();

        // Define o caminho onde a imagem será salva
        mkdir("../docs/cases/{$case}/", 0777, true);
        $outputFile = "../docs/cases/{$case}/qrcode-{$type}-{$case}.png";

        // Salva a imagem em um arquivo
        $result->saveToFile($outputFile);

        // Retorna o caminho do arquivo gerado
        return $outputFile;
    }

    function uploadQRCodeToCloudinary($filePath=null)
    {
        // Configura as credenciais da Cloudinary
        Configuration::instance([
            'cloud' => [
                'cloud_name' => 'dnjbo3gko',
                'api_key'    => '524318792312759',
                'api_secret' => 'hufjqONrlIkLyjcJdX2_0rLFB-g'
            ],
            'url' => [
                'secure' => true
            ]
        ]);
    
        // Realiza o upload do arquivo para a Cloudinary
        $uploadResult = (new UploadApi())->upload($filePath, [
            'folder' => 'qrcodes'
        ]);

        // Delete image from local storage
        unlink($filePath);        
    
        // Retorna a URL do arquivo na Cloudinary
        return $uploadResult['secure_url'];
    }

    function generateAndUploadQRCode($url=null, $case=null, $type=null)
    {
        // Gere o QR Code
        $qrCodeFilePath = generateQRCode($url, $case, $type);
    
        if ($qrCodeFilePath === 'URL inválida!') {
            echo $qrCodeFilePath;
            return;
        }
    
        // Envie o QR Code para a Cloudinary
        $cloudinaryUrl = uploadQRCodeToCloudinary($qrCodeFilePath);
    
        // Salve a URL no banco de dados
        $datas['url_'.$type] = $url;
        $datas['qrcode_'.$type] = $cloudinaryUrl;

        $where_params['value'] = $case;
        $where_params['column_name'] = 'token_case';

        $table_name = 'cases';
        return updateOnDB($datas, $table_name, $where_params);
    }

    function uploadDocumentToCloudinary($filePath=null, $case=null, $name_doc=null, $type=null)
    {
        // Configura as credenciais da Cloudinary
        Configuration::instance([
            'cloud' => [
                'cloud_name' => 'dnjbo3gko',
                'api_key'    => '524318792312759',
                'api_secret' => 'hufjqONrlIkLyjcJdX2_0rLFB-g'
            ],
            'url' => [
                'secure' => true
            ]
        ]);
    
        // Realiza o upload do arquivo para a Cloudinary
        $uploadResult = (new UploadApi())->upload($filePath, [
            'folder' => 'cases_documents'
        ]);

        // Delete image from local storage
        unlink($filePath);

        // Salve a URL no banco de dados
        $datas['url_document'] = $uploadResult['secure_url'];
        $datas['name_document'] = $name_doc;
        $datas['profile_document'] = $type;
        $datas['token_case'] = $case;

        $table_name = 'cases_documents';
        return saveOnDB($datas, $table_name);
    }

    function salvarArquivosCarregados($files, $destino, $token_case) {
        // Verifica se o diretório de destino existe, se não, cria o diretório
        
        if (!is_dir($destino)) {
            mkdir($destino, 0777, true);
        }
    
        // Array para armazenar os resultados do upload
        $resultados = [];
    
        // Percorre todos os arquivos em $_FILES
        foreach ($files as $campo => $arquivo) {
            // Verifica se foi carregado sem erros
            if ($arquivo['error'] === UPLOAD_ERR_OK) {
                $nomeTemporario = $arquivo['tmp_name'];
                $extensao = pathinfo($arquivo['name'], PATHINFO_EXTENSION);
                $nomeArquivo = $campo . '_' . $token_case . '.' . $extensao;
                $caminhoDestino = $destino . DIRECTORY_SEPARATOR . $nomeArquivo;
    
                // Move o arquivo para o destino
                if (move_uploaded_file($nomeTemporario, $caminhoDestino)) {
                    // Obtém a URL do arquivo
                    $urlArquivo = WEBURL . '/' . str_replace('../', '', $destino) . $nomeArquivo;
                    $resultados[$campo] = [
                        "message" => "Arquivo '$nomeArquivo' carregado com sucesso.",
                        "url_final" => $urlArquivo
                    ];
                } else {
                    $resultados[$campo] = [
                        "message" => "Erro ao mover o arquivo '$nomeArquivo'.",
                        "url_final" => null
                    ];
                }
            } else {
                $resultados[$campo] = [
                    "message" => "Erro no upload do arquivo '$arquivo[name]'. Código de erro: " . $arquivo['error'],
                    "url_final" => null
                ];
            }
        }
    
        return $resultados;
    }
?>
