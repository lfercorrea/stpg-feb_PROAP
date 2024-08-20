@extends('layout')
@section('title', $title)
@section('content')
    <div class="side-margins">
        <div class="contsainer">
            <div class="section-margins">
                <h5>Alterar dados do solicitante</h5>
            </div>
            <form action="{{ route('site.solicitante.store', ['id' => $solicitante->id]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="input-field col s12 m4">
                        <i class="material-icons prefix">account_circle</i>
                        <input id="nome" type="text" name="nome" class="validate" value="{{ old('nome', $solicitante->nome) }}" required>
                        <label for="nome">Nome completo</label>
                    </div>
                    <div class="input-field col s12 m3">
                        <input id="email" type="text" name="email" class="validate" value="{{ old('email', $solicitante->email) }}" disabled>
                        <label for="email">Endereço de email</label>
                        <span class="helper-text" data-error="Este endereço de email é inválido" data-success="right">O e-mail não pode ser alterado</span>
                    </div>
                    <div class="input-field col s12 m2">
                        <select name="tipo_solicitante" required>
                            <option value="{{ old('tipo_solicitante', $solicitante->tipo_solicitante) }}" selected>{{ old('tipo_solicitante', $solicitante->tipo_solicitante) }}</option>
                            <option value="Discente">Discente</option>
                            <option value="Docente Colaborador">Docente Colaborador</option>
                            <option value="Docente Permanente">Docente Permanente</option>
                        </select>
                        <label>Tipo de solicitante</label>
                    </div>
                    <div class="input-field col s12 m2">
                        <input id="nascimento" type="date" name="nascimento" class="validate" min="1900-01-01" max="2099-12-31" value="{{ old('nascimento', $solicitante->nascimento) }}" required>
                        <label for="nascimento">Data de nascimento</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12 m2">
                        <i class="material-icons prefix">phone</i>
                        <input id="telefone" type="tel" name="telefone" class="validate" value="{{ old('telefone', $solicitante->telefone) }}" required>
                        <label for="telefone">Telefone</label>
                    </div>
                    <div class="input-field col s12 m2">
                        <input id="cpf" type="text" name="cpf" class="validate" value="{{ old('cpf', $solicitante->cpf) }}" required>
                        <label for="cpf">CPF</label>
                    </div>
                    <div class="input-field col s12 m2">
                        <input id="rg" type="text" name="rg" class="validate" value="{{ old('rg', $solicitante->rg) }}" required>
                        <label for="rg">RG</label>
                    </div>
                    <div class="input-field col s12 m2">
                        <input id="rg_data_expedicao" type="date" name="rg_data_expedicao" class="validate" min="1900-01-01" max="2099-12-31" value="{{ old('rg_data_expedicao', $solicitante->rg_data_expedicao) }}" required>
                        <label for="rg_data_expedicao">Data de expedição do RG</label>
                    </div>
                    <div class="input-field col s12 m2">
                        <input id="rg_orgao_expedidor" type="text" name="rg_orgao_expedidor" class="validate" value="{{ old('rg_orgao_expedidor', $solicitante->rg_orgao_expedidor) }}" required>
                        <label for="rg_orgao_expedidor">Órgão expedidor do RG</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12 m6">
                        <textarea id="endereco_completo" name="endereco_completo" class="materialize-textarea">{{ $solicitante->endereco_completo }}</textarea>
                        <label for="endereco_completo">Endereço completo</label>
                    </div>
                    <div class="input-field col s12 m2">
                        <input id="banco" type="text" name="banco" class="validate" value="{{ old('banco', $solicitante->banco) }}" required>
                        <label for="banco">Nome do banco</label>
                    </div>
                    <div class="input-field col s12 m2">
                        <input id="banco_agencia" type="text" name="banco_agencia" class="validate" value="{{ old('banco_agencia', $solicitante->banco_agencia) }}" required>
                        <label for="banco_agencia">Código da agência</label>
                    </div>                    
                    <div class="input-field col s12 m2">
                        <input id="banco_conta" type="text" name="banco_conta" class="validate" value="{{ old('banco_conta', $solicitante->banco_conta) }}" required>
                        <label for="banco_conta">Número da conta</label>
                    </div>                    
                </div>
                <div class="alert print-hidden">
                    <b>IMPORTANTE</b>
                    <p>
                        É esperado que os dados pessoais dos solicitantes <b>jamais</b> precisem ser alterados manualmente. A idéia é que esses dados sejam atualizados automaticamente a cada nova importação dos CSVs.
                        No entanto, em alguns casos, pode ser necessário fazer correções. Um exemplo clássico: um discente ou técnico de laboratório faz uma requisição de material
                        (usando seu próprio endereço de email @unesp) em nome do docente do programa. Este tipo de inconsistência
                        não pode ocorrer, mas se ocorrer, a idéia é usar esta ferramenta para agilizar o processo em vez de solicitar ao docente que faça ele mesmo (sabe-se lá em qual lua) o preenchimento da solicitação.
                    </p>
                </div>
                <div class="container center">
                    <div class="col s12 center">
                        <a class="waves-effect waves-black btn-flat" onclick="history.back()">Voltar</a>
                        <button type="submit" class="btn waves-effect waves-light black">Salvar alterações</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection