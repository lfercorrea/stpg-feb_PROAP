{{-- @extends('layout') --}}
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>STPG-FEB - {{ $title }}</title>
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
                    <b>MODELO "A"</b>
                </div>
            </div>

            <table class="bordered">
                <tr class="mm7">
                    <td class="cell">PROJETO N°: <b>{{ $programa->projeto_capes }}</b></td>
                </tr>
                <tr>
                    <td class="title">RECIBO</td>
                </tr>
                <tr>
                    <td class="cell">
                        <div class="recibo">
                            Recebi da Fundação <b>CAPES/<u>{{ Str::upper($programa->coordenador) }}</b></u>
                            <br>
                            a importância de <b>R$ <u>{{ $valor_total }}</u> (<u>{{ $valor_extenso }}</u>),</b> em caráter eventual e sem vínculo empregatício, a título de <b><u>AUXÍLIO
                            FINANCEIRO A {{ $tipo_beneficiario[$solicitacao->solicitante->tipo_solicitante] }} ({{ $tipo_valor }})</u></b> no período de <b>{{ $periodo }}</b>
                            <b>
                                <div class="margin-top" style="margin-left: 3.4cm">
                                    VALOR DA REMUNERAÇÃO: R$ {{ $valor_total }}
                                </div>
                                Deduções (*)
                                <div style="margin-left: 4.4cm">
                                    Líquido recebido: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        R$ {{ $valor_total }}
                                </div>
                            </b>
                        </div>
                    </td>
                </tr>
            </table>
            <div class="text-small margin-bottom">
                (*) Não se aplica a diárias e sim a serviços prestados por pessoa física quando essa não possuir
                talonários de Nota Fiscal de Serviços. Só aplicar deduções (INSS, ISS etc.), quando for o caso.
            </div>
            <table class="black bordered">
                <tr>
                    <td class="title" colspan="2">IDENTIFICAÇÃO DO PRESTADOR DE SERVIÇO</td>
                </tr>
                <tr class="mm9">
                    <td class="cell text-top">Nome: <b>{{ Str::upper($solicitacao->solicitante->nome) }}</b></td>
                    <td class="cell text-top">CPF: <b>{{ $solicitacao->solicitante->cpf }}</b></td>
                </tr>
                <tr class="mm9">
                    <td class="cell text-top">Profissão: <b>{{ Str::upper($solicitacao->solicitante->tipo_solicitante . ' de pós-graduação') }}</b></td>
                    <td class="cell text-top">RG / Passaporte (se estrangeiro): <b>{{ $solicitacao->solicitante->rg }}</b></td>
                </tr>
                <tr class="mm9">
                    <td class="cell text-top" colspan="2">ENDEREÇO COMPLETO: <b>{{ Str::upper($solicitacao->solicitante->endereco_completo) }}</b></td>
                </tr>
            </table>
            <br>
            <table class="black bordered">
                <tr>
                    <td class="title" colspan="2">TESTEMUNHAS (na falta dos dados de identificação do Prestador de Serviço)</td>
                </tr>
                <tr class="mm9">
                    <td class="cell text-top">(1) Nome</td>
                    <td class="cell text-top">CPF</td>
                </tr>
                <tr class="mm5">
                    <td class="cell">Profissão:</td>
                    <td class="cell">RG</td>
                </tr>
                <tr class="mm9">
                    <td class="cell text-top">Endereço Completo:</td>
                    <td class="cell text-center text-bottom">Assinatura</td>
                </tr>
            </table>
            <div style="margin-top: 1px;"></div>
            <table class="black bordered">
                <tr class="mm9">
                    <td class="cell text-top">(2) Nome</td>
                    <td class="cell text-top">CPF</td>
                </tr>
                <tr class="mm5">
                    <td class="cell">Profissão:</td>
                    <td class="cell">RG</td>
                </tr>
                <tr class="mm9">
                    <td class="cell text-top">Endereço Completo:</td>
                    <td class="cell text-bottom text-center">Assinatura</td>
                </tr>
            </table>
            <br>
            <table class="black bordered">
                <tr>
                    <td class="title" colspan="2">ASSINATURAS BENEFICIÁRIO/PRESTADOR DO SERVIÇO</td>
                </tr>
                <tr colspan="2">
                    <td class="cell text-top">
                        Atesto que os serviços constantes do presente recibo foram prestados.
                        <br>
                        <br>
                        Em {{ $data_impressao }},
                        <br>
                        <br>
                        <br>
                    </td>
                    <td class="cell text-top">
                        Por ser verdade, firmo o presente recibo.
                        <br>
                        <br>
                        BAURU, {{ $data_extenso }}
                    </td>
                </tr>
                <tr>
                    <td class="cell center text-bottom">Assinatura do Beneficiário do Auxílio</td>
                    <td class="cell center"><b>Assinatura do Prestador de Serviço</b></td>
                </tr>
            </table>
            <div class="text-small margin-top margin-bottom center">
                <b>ATENÇÃO:</b> Utilizar este modelo quando ocorrer pagamento de diárias, bolsas ou remuneração de serviço
                    a pessoas físicas que não possuam talonários de Notas Fiscais de Serviços <b>(Outros Serviços de Terceiros - Pessoa Física)</b>.
                </b>
            </div>
        </div>
    </div>

</body>