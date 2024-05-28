@extends('layout')
@section('content')
    <div class="side-margins">

        <div class="section-margins">
            <h5>{{ $solicitante->nome }} ({{ $solicitante->email }})</h5>
        </div>
        <table class="compact-table striped responsive-table">
            <tr>
                <th>Endereço</th>
            </tr>
            <tr>
                <td>{{ $solicitante->endereco_completo }}</td>
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
                <td>{{ $solicitante->nascimento }}</td>
                <td>{{ $solicitante->cpf }}</td>
                <td>{{ $solicitante->rg }}</td>
                <td>{{ $solicitante->rg_orgao_expedidor }}</td>
                <td>{{ $solicitante->rg_data_expedicao }}</td>
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
                <td>{{ $solicitante->telefone }}</td>
                <td>{{ $solicitante->banco }}</td>
                <td>{{ $solicitante->banco_agencia }}</td>
                <td>{{ $solicitante->banco_conta }}</td>
            </tr>
        </table>
        <div class="section-margins">
            <h5>Solicitações</h5>
        </div>

        <div class="container center">
            <a class="btn black waves-effect waves-black" onclick="history.back()">Voltar</a>
        </div>

    </div>
@endsection