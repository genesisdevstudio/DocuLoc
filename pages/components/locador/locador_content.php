<div class="row max-w-80 mx-auto">
    <div class="col-12 d-flex justify-content-between mt-4">
        <img src="../../../assets/img/logos/logotipo.png" class="w-10" alt="logo doculoc" />
        <img src="#" alt="logo imoboliaria" />
    </div>

    <div class="col-12 text-center">
        <h3 class="mt-5 text-dark">Cadastro de Locador</h3>
        <h5 class="text-dark font-weight-normal">Seu caso de locação foi aberto.</h5>
        <h5 class="font-weight-normal">Por favor preencha os dados abaixo para prosseguirmos com sua locação.</h5>

        <hr />

        <p>
            OBS: os campos sinalizados com<span class="text-danger">&nbsp;*&nbsp;</span>são obrigatórios.
            Só será possível enviar o formulário com 100% dos campos obrigatórios preenchidos
        </p>
    </div>

    <hr />

    <div class="col-md-12 mx-auto mt-4">
        <div class="card mb-8">
            <form action=".<?=PATHURL;?>services/locador.php" method="post" enctype="multipart/form-data">
                <div class="card-body">
                    <!-- Informações Pessoais Step -->
                    <div class="row">
                        <p class="text-uppercase text-sm">INFORMAÇÕES PESSOAIS</p>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nome" class="form-control-label">Nome completo<span class="text-danger">&nbsp;*</span></label>
                                <input id="nome" class="form-control" name="info_pessoa_nome" type="text" placeholder="Digite seu nome completo" required />
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="profissao" class="form-control-label">Profissão</label>
                                <input id="profissao" class="form-control" name="info_pessoa_profissao" type="text" placeholder="Digite sua profissão" />
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="cpf" class="form-control-label">CPF<span class="text-danger">&nbsp;*</span></label>
                                <input id="cpf" class="form-control mask_cpf" name="info_pessoa_cpf" type="text" placeholder="Ex: 999.999.999-99" required />
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="rg" class="form-control-label">RG ou CNH<span class="text-danger">&nbsp;*</span></label>
                                <input id="rg" class="form-control mask_rgcnh" name="info_pessoa_rg_cnh" type="text" placeholder="Digite apenas números" required />
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="rg-anexo" class="form-control-label">RG ou CNH (anexo)<span class="text-danger">&nbsp;*</span></label>
                                <input id="rg-anexo" class="form-control" name="info_pessoa_rg_cnh_file" type="file" required />
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="estado-civil" class="form-control-label">Estado Civil<span class="text-danger">&nbsp;*</span></label>
                                <select id="estado-civil" class="form-control" name="info_pessoa_estado_civil" required>
                                    <option value="solteiro">Solteiro</option>
                                    <option value="casado">Casado</option>
                                    <option value="separado">Separado</option>
                                    <option value="divorciado">Divorciado</option>
                                    <option value="viuvo">Viúvo</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="certidao" class="form-control-label">Certidão de casamento ou nascimento (anexo)<span class="text-danger">&nbsp;*</span></label>
                                <input id="certidao" class="form-control" name="info_pessoa_certidao_casamento_nascimento_file" type="file" required>
                            </div>
                        </div>

                        <div class="col-md-12" id="regime-casamento" style="display: none;"></div>
                    </div>

                    <!-- Conjuge Step -->
                    <div class="row" id="row-conjuge" style="display: none;"></div>

                    <hr class="horizontal dark" />

                    <!-- Contato Step -->
                    <div class="row">
                        <p class="text-uppercase text-sm">Contato</p>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="logradouro" class="form-control-label">Logradouro<span class="text-danger">&nbsp;*</span></label>
                                <input id="logradouro" class="form-control" name="contato_logradouro" type="text" placeholder="Rua, Bairro, Nº, CEP, Cidade-Estado" required />
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="comprovante-endereco" class="form-control-label">Comprovante de Endereço (anexo)<span class="text-danger">&nbsp;*</span></label>
                                <input id="comprovante-endereco" class="form-control" name="contato_comprovante_endereco_file" type="file" required />
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email" class="form-control-label">E-mail<span class="text-danger">&nbsp;*</span></label>
                                <input id="email" class="form-control" name="contato_email" type="email" placeholder="exemplo@exemplo.com" required />
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="telefone" class="form-control-label">Telefone<span class="text-danger">&nbsp;*</span></label>
                                <input id="telefone" class="form-control mask_telefone" name="contato_telefone" type="text" placeholder="(99) 99999-9999" required />
                            </div>
                        </div>
                    </div>

                    <hr class="horizontal dark" />

                    <!-- Dados Bancários Step -->
                    <div class="row">
                        <p class="text-uppercase text-sm">DADOS BANCÁRIOS</p>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nome-banco" class="form-control-label">Nome do banco <span class="text-danger">&nbsp;*</span></label>
                                <input id="nome-banco" class="form-control" name="dados_bancarios_nome_banco" type="text" placeholder="Ex: Banco do Brasil" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="agencia-banco" class="form-control-label">Agência <span class="text-danger">&nbsp;*</span></label>
                                <input id="agencia-banco" class="form-control mask_number" name="dados_bancarios_agencia" type="text" placeholder="Ex: 1234" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="numero-banco" class="form-control-label">Número da conta <span class="text-danger">&nbsp;*</span></label>
                                <input id="numero-banco" class="form-control" name="dados_bancarios_conta" type="text" placeholder="Ex: 12345-6" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="pix-banco" class="form-control-label">Chave pix <span class="text-danger">&nbsp;*</span></label>
                                <input id="pix-banco" class="form-control" name="dados_bancarios_pix" type="text" placeholder="Ex: exemplo@exemplo.com" required>
                            </div>
                        </div>
                    </div>

                    <hr class="horizontal dark">

                    <!-- Dados Imóvel Step -->
                    <div class="row">
                        <p class="text-uppercase text-sm">DADOS IMÓVEL</p>

                        <div class="col-md-12">
                            <div class="form-check mb-3">
                                <input id="residencial" class="form-check-input" name="dados_imovel_tipo" type="radio" value="residencial" onclick="mostrarSelecao()">
                                <label for="residencial" class="custom-control-label">Residencial</label>
                            </div>

                            <div class="form-check mb-3">
                                <input id="comercial" class="form-check-input" name="dados_imovel_tipo" type="radio" value="comercial" onclick="mostrarSelecao()">
                                <label for="comercial" class="custom-control-label">Comercial</label>
                            </div>
                        </div>

                        <div class="row" id="result"></div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="valor-imovel" class="form-control-label">Valor do aluguel<span class="text-danger">&nbsp;*</span></label>
                                <input id="valor-imovel" class="form-control mask_money" name="dados_imovel_valo_aluguel" type="text" placeholder="Ex: 1.600,00" required>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="numero-matricula-imovel" class="form-control-label">Número da matrícula<span class="text-danger">&nbsp;*</span></label>
                                <input id="numero-matricula-imovel" class="form-control" name="dados_imovel_matricula" type="text" placeholder="Digite aqui" required>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="cartorio-matricula-imovel" class="form-control-label">Cartório<span class="text-danger">&nbsp;*</span></label>
                                <input id="cartorio-matricula-imovel" class="form-control" name="dados_imovel_cartorio" type="text" placeholder="" required>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="cidade-matricula-imovel" class="form-control-label">Cidade<span class="text-danger">&nbsp;*</span></label>
                                <input id="cidade-matricula-imovel" class="form-control" name="dados_imovel_cidade" type="text" placeholder="" required>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="matricula-escritura-contrato-imovel" class="form-control-label">Matrícula ou Escritura ou Contrato (anexo)</label>
                                <input id="matricula-escritura-contrato-imovel" class="form-control" name="dados_imovel_matricula_escritura_contrato_file" type="file" />
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="condominio-valor-imovel" class="form-control-label">Valor do condomínio <span class="text-danger">&nbsp;*</span></label>
                                <input id="condominio-valor-imovel" class="form-control mask_money" name="dados_imovel_valor_condominio" type="text" placeholder="Ex: 1.000,00" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="condominio-anexo-imovel" class="form-control-label">Boleto do condomínio (anexo)</label>
                                <input id="condominio-anexo-imovel" class="form-control" name="dados_imovel_boleto_condominio_file" type="file" />
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="iptu-imovel" class="form-control-label">Valor do IPTU <span class="text-danger">&nbsp;*</span></label>
                                <input id="iptu-imovel" class="form-control mask_money" name="dados_imovel_valor_iptu" type="text" placeholder="Ex: 1.000,00" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="iptu-anexo-imovel" class="form-control-label">IPTU (anexo)</label>
                                <input id="iptu-anexo-imovel" class="form-control" name="dados_imovel_iptu_file" type="file" />
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <input type="hidden" name="token_case" value="<?=$token_case;?>">
                        <input class="btn btn-success" type="submit" name="save_locador" value="Enviar Dados">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>