@extends('layouts.main')
@section('content')

    @include('layouts.reserva')

    <form method="post" action="{{ url('/hospede/') }}" accept-charset="utf-8" enctype="multipart/form-data">
        
        {{ csrf_field() }}

        <hr />
        <h3><span class="fa fa-user"></span> Hóspede nº {{ $viewModel->Pos }}</h3>
        <hr />

        <div class="form-group">
            <div class="col-md-6"> 
                <label for="cliente_CgcCpf">CPF</label>
                <input type="number" 
                    class="form-control" 
                    name="cliente_CgcCpf" size="50"
                    id="cpf"
                    value="{{ $viewModel->cliente()->CgcCpf }}" 
                    <?php echo (
                        $viewModel->hospede()->NrHospede > 0 && 
                        trim(strlen($viewModel->cliente()->CgcCpf)) > 0) ? 'readonly' : '' ?>
                    required>
            </div>
            <div class="col-md-6">        
                <label for="cliente_Nome">Nome Completo</label>
                <input type="text" 
                    class="form-control" 
                    name="cliente_Nome" size="50"
                    value="{{ $viewModel->cliente()->Nome }}" required>
            </div>
            <div class="col-md-6">        
                <label for="cliente_DtNasc">Dt. Nascimento</label>
                <input type="date" 
                    class="form-control" 
                    name="cliente_DtNasc" size="50"
                    value="{{ $viewModel->cliente()->dtnasc() }}">
            </div>
            <div class="col-md-6">        
                <label for="cliente_Email">Email</label>
                <input type="text" 
                    class="form-control" 
                    name="cliente_Email" size="50"
                    value="{{ $viewModel->cliente()->Email }}" required>
            </div>
            <div class="col-md-6">        
                <label for="cliente_FoneRes">Fone Residencial</label>
                <input type="text" 
                    class="form-control" 
                    name="cliente_FoneRes" size="50"
                    value="{{ $viewModel->cliente()->FoneRes }}" required>
            </div>
            <div class="col-md-6">        
                <label for="cliente_FoneCom">Fone Comercial</label>
                <input type="text" 
                    class="form-control" 
                    name="cliente_FoneCom" size="50"
                    value="{{ $viewModel->cliente()->FoneCom }}" required>
            </div>
            <div class="col-md-6">        
                <label for="cliente_FoneCel">Fone Celular</label>
                <input type="text" 
                    class="form-control" 
                    name="cliente_FoneCel" size="50"
                    value="{{ $viewModel->cliente()->FoneCel }}" required>
            </div>
            <div class="col-md-6">        
                <label for="cliente_Endereco">Endereço</label>
                <input type="text" 
                    class="form-control" 
                    name="cliente_Endereco" size="50"
                    value="{{ $viewModel->cliente()->Endereco }}" required>
            </div>
            <div class="col-md-6">        
                <label for="cliente_Nr">Número</label>
                <input type="text" 
                    class="form-control" 
                    name="cliente_Nr" size="50"
                    value="{{ $viewModel->cliente()->Nr }}" required>
            </div>
            <div class="col-md-6">        
                <label for="cliente_Comp">Complemento</label>
                <input type="text" 
                    class="form-control" 
                    name="cliente_Comp" size="50"
                    value="{{ $viewModel->cliente()->Comp }}">
            </div>
            <div class="col-md-6">        
                <label for="cliente_Bairro">Bairro</label>
                <input type="text" 
                    class="form-control" 
                    name="cliente_Bairro" size="50"
                    value="{{ $viewModel->cliente()->Bairro }}" required>
            </div>
            <div class="col-md-6">        
                <label for="cliente_Cidade">Cidade</label>
                <input type="text" 
                    class="form-control" 
                    name="cliente_Cidade" size="50"
                    value="{{ $viewModel->cliente()->Cidade }}" required>
            </div>

            <div class="col-md-6">        
                <label for="cliente_UF">Estado</label>
                <select 
                    class="form-control" 
                    name="cliente_UF"
                    id="cliente_UF"
                    value="{{ $viewModel->cliente()->UF}}" required>
                    <option id="AC" value="AC">Acre</option>
                    <option id="AL" value="AL">Alagoas</option>
                    <option id="AP" value="AP">Amapá</option>
                    <option id="AM" value="AM">Amazonas</option>
                    <option id="AM" value="AM">Bahia</option>
                    <option id="CE" value="CE">Ceará</option>
                    <option id="DF" value="DF">Distrito Federal</option>
                    <option id="ES" value="ES">Espírito Santo</option>
                    <option id="GO" value="GO">Goiás</option>
                    <option id="MA" value="MA">Maranhão</option>
                    <option id="MT" value="MT">Mato Grosso</option>
                    <option id="MS" value="MS">Mato Grosso do Sul</option>
                    <option id="MG" value="MG">Minas Gerais</option>
                    <option id="PA" value="PA">Pará</option>
                    <option id="PB" value="PB">Paraíba</option>
                    <option id="PR" value="PR">Paraná</option>
                    <option id="PE" value="PE">Pernambuco</option>
                    <option id="PI" value="PI">Piauí</option>
                    <option id="RJ" value="RJ">Rio de Janeiro</option>
                    <option id="RN" value="RN">Rio Grande do Norte</option>
                    <option id="RS" value="RS">Rio Grande do Sul</option>
                    <option id="RO" value="RO">Rondônia</option>
                    <option id="RR" value="RR">Roraima</option>
                    <option id="SC" value="SC">Santa Catarina</option>
                    <option id="SP" value="SP">São Paulo</option>
                    <option id="SE" value="SE">Sergipe</option>
                    <option id="TO" value="TO">Tocantins</option>
                </select>                
            </div>

            <div class="col-md-6">        
                <label for="cliente_Cep">CEP</label>
                <input type="number" 
                    class="form-control" 
                    name="cliente_Cep"
                    value="{{ $viewModel->cliente()->Cep }}" required>
            </div>

            <div class="col-md-6">        
                <label for="hospede_ObsHospede">Observação</label>
                <textarea type="text" rows="6" 
                    class="form-control" 
                    name="hospede_ObsHospede">{{ $viewModel->hospede()->ObsHospede }}</textarea>
            </div>

            <div class="col-md-6">        
            
            @if ($viewModel->hotel()->UsaFotoPreCheckin == 'S')
                <div class="avatar-upload">
                    <input type='file' name="imageUpload" id="imageUpload" style="display:none;" accept=".png, .jpg, .jpeg" />                
                    <div class="avatar-preview">
                        <div id="imagePreview" 
                        style="background-image: url(/camera/{{ $viewModel->cliente()->CodCli }}/{{ $viewModel->reserva()->ChaveIdentificacao }});">
                    </div>
                </div>  
            @endif
                <!--         
                    <input type='file' id="imageUpload" style="display:none;" accept=".png, .jpg, .jpeg" />
                    <img id="camera" src= "/camera"/>
                    <div class="avatar-preview">
                    </div>                
                -->

            </div>

        </div>     
       
        <a class="btn btn-lg btn-secondary" href="/hospedes/{{ $viewModel->reserva()->ChaveIdentificacao }}">
            Voltar
        </a>

        <button style="max-width:200px" class="btn btn-lg btn-primary" type="submit">Gravar</button>

        <input type="hidden" value="{{ $viewModel->reserva()->ChaveIdentificacao }}" name="id">
        <input type="hidden" value="{{ $viewModel->hospede()->NrHospede }}" name="hospede">

    </form>

    <script>

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#imagePreview').css('background-image', 'url('+e.target.result +')');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function TestaCPF(strCPF) {
            
            function Filled(v,p){
                for (var i=0;i<v.length;i++)
                  if (v.substr(i,1) !== p) return false;
                return true;
            }

            var Soma;
            var Resto;
            Soma = 0;

            if (Filled(strCPF,'0')) return false;
            if (Filled(strCPF,'1')) return false;
            if (Filled(strCPF,'2')) return false;
            if (Filled(strCPF,'3')) return false;
            if (Filled(strCPF,'4')) return false;
            if (Filled(strCPF,'5')) return false;
            if (Filled(strCPF,'6')) return false;
            if (Filled(strCPF,'7')) return false;
            if (Filled(strCPF,'8')) return false;
            if (Filled(strCPF,'9')) return false;

            for (i=1; i<=9; i++) Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (11 - i);
            Resto = (Soma * 10) % 11;

            if ((Resto == 10) || (Resto == 11))  Resto = 0;
            if (Resto != parseInt(strCPF.substring(9, 10)) ) return false;

            Soma = 0;
            for (i = 1; i <= 10; i++) Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (12 - i);
            Resto = (Soma * 10) % 11;

            if ((Resto == 10) || (Resto == 11))  Resto = 0;
            if (Resto != parseInt(strCPF.substring(10, 11) ) ) return false;
            return true;
        }

        $(function() {

            var uf = document.getElementById('cliente_UF').getAttribute('value');
            if (uf) document.getElementById(uf).setAttribute('selected', 'selected');

            $('#cpf').change(function(){
                var v = $('#cpf').val().trim();
                if (v.length !== 11 || !TestaCPF(v)) $('#cpf').val(null);
            });

            $('#imagePreview').click(function(){ $('#imageUpload').trigger('click'); });
            $("#imageUpload").change(function() { readURL(this); });

        });

    </script>

@endsection