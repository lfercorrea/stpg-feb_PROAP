{{-- @extends('layout') --}}
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>STPG-FEB -  - {{ $title }}</title>
    <link rel="stylesheet" href="{{ asset('css/materialize.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('storage/static/images/favicon.ico') }}">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
    <div class="page">
        <div class="relatorio abnt-margins">
            <div class="relatorio-header">
                <div class="capes-logo">
                    <img src="{{ asset('storage/static/images/capes.png') }}" class="capes-logo">
                </div>
                <div class="capes-header">
                    <b>
                        CAPES - COORDENAÇÃO DE APERFEIÇOAMENTO DE PESSOAL DE NÍVEL SUPERIOR
                        <br>
                        <i>
                            CNPJ 00.889.834/0001-08
                            <br>
                            Endereço: SBN Quadra 02 Lote 06 Bloco L, CEP 70040-020, Brasília - DF
                        </i>
                        <br>
                        <br>
                    </b>
                </div>
                <div class="recibo-tipo text-center">
                    <b>MODELO "B"</b>
                </div>
            </div>

            <table class="bordered">
                <tr class="mm7">
                    <td class="cell" colspan="2">N.° AUXPE: <b>{{ $projeto_capes }}</b></td>
                </tr>
                <tr>
                    <td class="title" colspan="2">RECIBO</td>
                </tr>
                <tr>
                    <td class="cell">
                        Beneficiário (Titular do Auxílio): <b>{{ Str::upper($solicitacao->solicitante->nome) }}</b>
                    </td>
                    <td class="cell">
                        CPF: <b>{{ $solicitacao->solicitante->cpf }}</b>
                    </td>
                </tr>
                <tr>
                    <td class="cell" colspan="2">
                        <div class="recibo">
                            Declaro, junto a Coordenação de Aperfeiçoamento de Pessoal de Nível Superior - CAPES, que utilizei
                            parte dos recursos de custeio para o Projeto de Pesquisa n.º <b><u>{{ $projeto_capes }}</u></b>,
                            no valor de <b>R$ <u>{{ $valor_total }}</u> (<u>{{ $valor_extenso }}</u>),</b>
                            a título de em caráter eventual e sem vínculo empregatício, a título de (<b>{{ $tipo_valor }}</b>)
                            no período de <b>{{ $periodo }}</b>.
                            <br>
                            <div style="margin-left: 4cm">
                                (&nbsp;&nbsp;&nbsp;&nbsp;) Reembolso
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
            <br>
            <table class="black bordered">
                <tr>
                    <td class="title" style="width: 35%">OBSERVAÇÃO</td>
                    <td class="title" style="width: 65%">ASSINATURA DO BENEFICIÁRIO</td>
                </tr>
                <tr class="mm9">
                    <td class="cell text-top">{{ Str::upper($observacao) }}</td>
                    <td class="cell text-top">
                        BAURU, {{ $data_extenso }}.
                        <br>
                        <br>
                        <br>
                        <div class="center">
                            ________________________________________________
                            <br>
                            Assinatura
                        </div>
                    </td>
                </tr>
            </table>
            <br>
            <div class="text-small margin-top margin-bottom center">
                <b>IMPORTANTE: Este modelo deve ser utilizado <u>APENAS</u> para pagamento ao próprio Beneficiário do AUXPE.</b>
            </div>
        </div>
    </div>

</body>