<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

<script>
  $(document).click(function() {
    $('.mask_cpf').mask('000.000.000-09');
    $('.mask_cnpj').mask('00.000.000/0000-09');
    $('.mask_rgcnh').mask('000000000000009');
    $('.mask_number').mask('0000000009');
    $('.mask_money').mask('#.##0,00', {
      reverse: true
    });
    $('.mask_telefone').mask('(00) 0000-00009');
  });

  $(document).ready(function() {
    // $('.mask_money').blur(function() {
    //   if ($(this).unmask().length < 2) {
    //     if ($(this).unmask().length < 2) {
    //       let valor = '00' + $(this).unmask().val();

    //       $(this).val(valor);
    //       $(this).mask('#.##0,00', {
    //         reverse: true
    //       });
    //     } else {
    //       let valor = '0' + $(this).unmask().val();

    //       $(this).val(valor);
    //       $(this).mask('#.##0,00', {
    //         reverse: true
    //       });
    //     }
    //   }
    // });

    $('.mask_telefone').blur(function(event) {
      if($(this).val().length == 15){ // Celular com 9 dígitos + 2 dígitos DDD e 4 da máscara
          $(this).mask('(00) 00000-0009');
      } else {
          $(this).mask('(00) 0000-00009');
      }
    });

    $('#estado-civil').change(function() {
      $('#regime-casamento').css('display', 'none');
      $('#row-conjuge').css('display', 'none');

      let estado_civil = $(this).val();
      if (estado_civil === 'casado') {
        adicionaHtmlRegimeCasamento();
        adicionaHtmlRowConjuge();
      } else {
        removeHtmlRegimeCasamento();
        removeHtmlRowConjuge();
      }
    });

    $('input[name="dados_imovel_tipo"]').click(function() {
      let tipo = $(this).val();
      
      if (tipo === 'comercial') {
        adicionaHtmlImovelComercial();
      } else {
        removeHtmlImovelComercial();
      }
    });

    function adicionaHtmlRegimeCasamento() {
      $('#regime-casamento').html(`
        <div class="form-group">
          <label for="regime-de-casamento" class="form-control-label">Regime de casamento<span class="text-danger">&nbsp;*</span></label>
          
          <select id="regime-de-casamento" class="form-control" name="info_pessoa_regime_casamento" name="regime-de-casamento" required>
            <option value="comunhao-parcial-de-bens">Comunhão Parcial de Bens</option>
            <option value="comunhao-universal-de-bens">Comunhão Universal de Bens</option>
            <option value="separacao-total-de-bens">Separação Total de Bens</option>
          </select>
        </div>
      `);

      $('#regime-casamento').css('display', 'block');
    }

    function adicionaHtmlRowConjuge() {
      $('#row-conjuge').html(`
        <p class="text-uppercase text-sm">INFORMAÇÕES CONJUGÊ</p>
        
        <div class="col-md-6">
          <div class="form-group">
            <label for="nome_conjuge" class="form-control-label">Nome completo<span class="text-danger">&nbsp;*</span></label>
            
            <input id="nome_conjuge" class="form-control" name="info_pessoa_nome_conjuge" type="text" placeholder="Digite o nome completo" required />
          </div>
        </div>
        
        <div class="col-md-6">
          <div class="form-group">
            <label for="profissao_conjuge" class="form-control-label">Profissão</label>
            
            <input id="profissao_conjuge" class="form-control" name="info_pessoa_profissao_conjuge" type="text" placeholder="Digite a profissão" />
          </div>
        </div>
        
        <div class="col-md-4">
          <div class="form-group">
            <label for="cpf_conjuge" class="form-control-label">CPF<span class="text-danger">&nbsp;*</span></label>
            
            <input id="cpf_conjuge" class="form-control mask_cpf" name="info_pessoa_cpf_conjuge" type="text" placeholder="Ex: 999.999.999-99" required />
          </div>
        </div>
        
        <div class="col-md-4">
          <div class="form-group">
            <label for="rg_conjuge" class="form-control-label">RG ou CNH<span class="text-danger">&nbsp;*</span></label>
            
            <input id="rg_conjuge" class="form-control mask_rgcnh" name="info_pessoa_rg_cnh_conjuge" type="text" placeholder="Digite apenas números" required />
          </div>
        </div>
        
        <div class="col-md-4">
          <div class="form-group">
            <label for="rg_anexo_conjuge" class="form-control-label">RG ou CNH (anexo)<span class="text-danger">&nbsp;*</span></label>
            
            <input id="rg_anexo_conjuge" class="form-control" name="info_pessoa_rg_cnh_conjuge_file" type="file" required />
          </div>
        </div>
      `);
      $('#row-conjuge').css('display', 'flex');
    }

    function removeHtmlRegimeCasamento() {
      $('#regime-casamento').empty();
      $('#regime-casamento').css('display', 'none');
    }

    function removeHtmlRowConjuge() {
      $('#row-conjuge').empty();
      $('#row-conjuge').css('display', 'none');
    }

    function adicionaHtmlImovelComercial() {
      $('#result').html(`
        <div class="col-md-12 mb-3 d-flex align-items-center">
          <div class="col-md-2">
            <div class="form-check">
              <input type="checkbox" name="dados_imovel_planta_comercial" class="form-check-input">

              <label class="custom-control-label">Possui planta comercial?</label>
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              <label class="form-control-label">Planta comercial (anexo)</label>

              <input name="dados_imovel_planta_comercial_file" type="file" class="form-control">
            </div>
          </div>
        </div>

        <div class="col-md-12 mb-3 d-flex align-items-center">
          <div class="col-md-2">
            <div class="form-check">
              <input type="checkbox" name="dados_imovel_habitese_comercial" class="form-check-input">

              <label class="custom-control-label">Possui habite-se comercial?</label>
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              <label class="form-control-label">Habite-se comercial (anexo)</label>

              <input name="dados_imovel_habitese_comercial_file" type="file" class="form-control">
            </div>
          </div>          
        </div>

        <div class="col-md-12 mb-3 d-flex align-items-center">
          <div class="col-md-2">
            <div class="form-check">
              <input type="checkbox" name="dados_imovel_avcb" class="form-check-input">

              <label class="custom-control-label">Possui AVCB?</label>
            </div>
          </div>          

          <div class="col-md-4">
            <div class="form-group">
              <label class="form-control-label">AVCB (anexo)</label>

              <input name="dados_imovel_avcb_file" type="file" class="form-control">
            </div>
          </div>
        </div>
      `);
    }

    function removeHtmlImovelComercial() {
      $('#result').empty();
    }
  });
</script>