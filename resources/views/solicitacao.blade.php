@extends('layout')
@section('content')
    <div class="side-margins">

        <div class="section-margins">
            <h5>Solicitação</h5>
        </div>
        <table class="compact-table striped responsive-table">
            <tr>
                <th>Solicitante</th>
            </tr>
            <tr>
                <td>{{ $solicitacao->solicitante->nome }} ({{ $solicitacao->solicitante->email }})</td>
            </tr>
        </table>
        <table class="compact-table striped responsive-table">
            <tr>
                <th>Endereço</th>
            </tr>
            <tr>
                <td>{{ $solicitacao->solicitante->endereco_completo }}</td>
            </tr>
        </table>
        <table class="compact-table striped responsive-table">
            <tr>
                <th>Nascimento</th>
                <th>CPF</th>
                <th>RG</th>
                <th>Órgão emissor</th>
                <th>Data de emissão</th>
            </tr>
            <tr>
                <td>{{ $solicitacao->solicitante->nascimento }}</td>
                <td>{{ $solicitacao->solicitante->cpf }}</td>
                <td>{{ $solicitacao->solicitante->rg }}</td>
                <td>{{ $solicitacao->solicitante->rg_orgao_expedidor }}</td>
                <td>{{ $solicitacao->solicitante->rg_data_expedicao }}</td>
            </tr>
        </table>
        <table class="compact-table striped responsive-table">
            <tr>
                <th>Telefone</th>
                <th>Banco</th>
                <th>Agência</th>
                <th>Conta</th>
            </tr>
            <tr>
                <td>{{ $solicitacao->solicitante->telefone }}</td>
                <td>{{ $solicitacao->solicitante->banco }}</td>
                <td>{{ $solicitacao->solicitante->banco_agencia }}</td>
                <td>{{ $solicitacao->solicitante->banco_conta }}</td>
            </tr>
        </table>

        

        <div class="container center">
            <a class="btn black waves-effect waves-black" onclick="history.back()">Voltar</a>
        </div>

    </div>
@endsection