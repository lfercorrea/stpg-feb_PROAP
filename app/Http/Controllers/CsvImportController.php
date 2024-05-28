<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;
use App\Imports\SolicitacoesDiscentesImport;
use App\Imports\SolicitacoesDocentesImport;
use App\Models\Atividade;
use App\Models\Evento;
use App\Models\Material;
use App\Models\Servico;
use App\Models\ImportacoesDiscentes;
use App\Models\Programa;
use App\Models\ProgramaCategoria;
use App\Models\Solicitacao;
use App\Models\SolicitacaoTipo;
use App\Models\Solicitante;

class CsvImportController extends Controller
{
    public function show_import_discentes_form() {
        return view('import_discentes');
    }

    public function show_import_docentes_form() {
        return view('import_docentes');
    }

    public function show() {
        return view('importacoes');
    }

    public function import_discentes(Request $request) {
        $request->validate([
            'file' => 'required|mimes:csv,txt',
        ]);

        try{
            /**
             * realimenta a tabela importacoes
             */
            ImportacoesDiscentes::truncate();
            Excel::import(new SolicitacoesDiscentesImport, $request->file('file'));
            ImportacoesDiscentes::destroy(1); // passa o rodo na linha do csv que contem os cabeçalhos
            ImportacoesDiscentes::whereNull('tipo_solicitante')->update(['tipo_solicitante' => 'Discente']);
            $importacoes = ImportacoesDiscentes::all();

            /**
             * realimenta a tabela de programas conforme a planilha
             */
            $programas_importados_distintos = DB::table('importacoes_discentes')
                ->select('programa')
                ->distinct()
                ->get();
                
            foreach($programas_importados_distintos as $distinto) {
                Programa::firstOrCreate([
                    'nome' => $distinto->programa,
                ]);
            }

            /**
             * realimenta a tabela de programa_categorias conforme a planilha
             */
            $programa_categorias_importados_distintos = DB::table('importacoes_discentes')
                ->select('categoria')
                ->distinct()
                ->get();

            foreach($programa_categorias_importados_distintos as $distinto) {
                ProgramaCategoria::firstOrCreate([
                    'nome' => $distinto->categoria,
                ]);
            }

            /**
             * realimenta a tabela de solicitantes, ignorando duplicidades
             * de acordo com email unico
             */
            $solicitantes_importados = DB::table('importacoes_discentes')
                ->select('nome', 'tipo_solicitante', 'email', 'programa', 'categoria', 'cpf', 'rg', 'rg_data_expedicao', 'rg_orgao_expedidor', 'nascimento', 'endereco_completo', 'telefone', 'banco', 'banco_agencia', 'banco_conta')
                ->get();
            $solicitantes_importados_distintos = $solicitantes_importados->unique('email');
            /**
             * garantir que apenas os itens com combinações únicas de 'email' e 'tipo_solicitante' sejam mantidos no resultado
             */
            // $solicitantes_importados_distintos = $solicitantes_importados->unique(['email', 'tipo_solicitante']);

            foreach($solicitantes_importados_distintos as $distinto) {
                Solicitante::firstOrCreate([
                    'email' => $distinto->email,
                ], [
                    'email' => $distinto->email,
                    'nome' => $distinto->nome,
                    'tipo_solicitante' => $distinto->tipo_solicitante,
                    'cpf' => $distinto->cpf,
                    'rg' => $distinto->rg,
                    'rg_data_expedicao' => $distinto->rg_data_expedicao,
                    'rg_orgao_expedidor' => $distinto->rg_orgao_expedidor,
                    'nascimento' => $distinto->nascimento,
                    'endereco_completo' => $distinto->endereco_completo,
                    'telefone' => $distinto->telefone,
                    'banco' => $distinto->banco,
                    'banco_agencia' => $distinto->banco_agencia,
                    'banco_conta' => $distinto->banco_conta,
                ]);
            }

            /**
             * realimenta a tabela de tipos de solicitacao e injeta na tabela de categorias
             */
            $tipos_solicitacao_distintos = DB::table('importacoes_discentes')
                ->select('tipo_solicitacao')
                ->distinct('tipo_solicitacao')
                ->get();
                
            foreach($tipos_solicitacao_distintos as $distinto) {
                SolicitacaoTipo::firstOrCreate([
                    'nome' => $distinto->tipo_solicitacao,
                ]);
            }

            /**
             * segmentar conforme tipo de evento
             */
            foreach($importacoes as $importacao) {
                switch($importacao->tipo_solicitacao) {
                    case 'Auxílio para Participação em Evento':
                        if(!empty($importacao->evento_nome)) {
                            Evento::firstOrCreate([
                                'importacao_id' => $importacao->id,
                            ], [
                                'nome' => $importacao->evento_nome,
                                'local' => $importacao->evento_local,
                                'periodo' => $importacao->evento_periodo,
                                'site_evento' => $importacao->evento_site_evento,
                                'titulo_trabalho' => $importacao->evento_titulo_trabalho,
                                'forma_participacao' => $importacao->evento_forma_participacao,
                                'valor_inscricao' => $importacao->evento_valor_inscricao,
                                'valor_passagens' => $importacao->evento_valor_passagens,
                                'valor_diarias' => $importacao->evento_valor_diarias,
                                'justificativa' => $importacao->evento_justificativa,
                                'ja_solicitou_recurso' => $importacao->evento_ja_solicitou_recurso,
                                'artigo_copia' => $importacao->evento_artigo_copia,
                                'artigo_aceite' => $importacao->evento_artigo_aceite,
                                'parecer_orientador' => $importacao->evento_parecer_orientador,
                                'orcamento_passagens' => $importacao->evento_orcamento_passagens,
                                'carimbo_data_hora' => $importacao->carimbo_data_hora,
                                'importacao_id' => $importacao->id,
                            ]);
                        }
                        break;
                    case 'Auxílio para Pesquisa de Campo':
                        if(!empty($importacao->atividade_descricao)) {
                            Atividade::firstOrCreate([
                                'importacao_id' => $importacao->id,
                            ], [
                                'descricao' => $importacao->atividade_descricao,
                                'local' => $importacao->atividade_local,
                                'periodo' => $importacao->atividade_periodo,
                                'valor_diarias' => $importacao->atividade_valor_diarias,
                                'valor_passagens' => $importacao->atividade_valor_passagens,
                                'justificativa' => $importacao->atividade_justificativa,
                                'carta_convite' => $importacao->atividade_carta_convite,
                                'parecer_orientador' => $importacao->atividade_parecer_orientador,
                                'orcamento_passagens' => $importacao->atividade_orcamento_passagens,
                                'carimbo_data_hora' => $importacao->carimbo_data_hora,
                                'importacao_id' => $importacao->id,
                            ]);
                        }
                        break;
                    case 'Aquisição de Material':
                        if(!empty($importacao->material_descricao)) {
                            Material::firstOrCreate([
                                'importacao_id' => $importacao->id,
                            ], [
                                'descricao' => $importacao->material_descricao,
                                'valor' => $importacao->material_valor,
                                'justificativa' => $importacao->material_justificativa,
                                'ja_solicitou_recurso' => $importacao->material_ja_solicitou_recurso,
                                'orcamento' => $importacao->material_orcamento,
                                'parecer_orientador' => $importacao->material_parecer_orientador,
                                'carimbo_data_hora' => $importacao->carimbo_data_hora,
                                'importacao_id' => $importacao->id,
                            ]);
                        }
                        break;
                    case 'Contratação de Serviço':
                        if(!empty($importacao->servico_tipo)) {
                            Servico::firstOrCreate([
                                'importacao_id' => $importacao->id,
                            ], [
                                'tipo' => $importacao->servico_tipo,
                                'titulo_artigo' => $importacao->servico_titulo_artigo,
                                'valor' => $importacao->servico_valor,
                                'justificativa' => $importacao->servico_justificativa,
                                'artigo_a_traduzir' => $importacao->servico_artigo_a_traduzir,
                                'orcamento' => $importacao->servico_orcamento,
                                'parecer_orientador' => $importacao->servico_parecer_orientador,
                                'carimbo_data_hora' => $importacao->carimbo_data_hora,
                                'importacao_id' => $importacao->id,
                            ]);
                        }
                        break;
                }
            }

            /**
             * finalmente, a tabela de solicitacoes que também fornecerá os relacionamentos
             */
            foreach($importacoes as $importacao) {
                $dados = [
                    'solicitante_id' => Solicitante::where('email', $importacao->email)->value('id'),
                    'programa_id' => Programa::where('nome', $importacao->programa)->value('id'),
                    'programa_categoria_id' => ProgramaCategoria::where('nome', $importacao->categoria)->value('id'),
                    'tipo_solicitacao_id' => SolicitacaoTipo::where('nome', $importacao->tipo_solicitacao)->value('id'),
                    'nome_do_orientador' => $importacao->nome_do_orientador,
                    'carimbo_data_hora' => $importacao->carimbo_data_hora,
                    'importacao_id' => $importacao->id,
                ];

                switch($importacao->tipo_solicitacao) {
                    case 'Auxílio para Pesquisa de Campo':
                        if(!empty($importacao->atividade_descricao)) {
                            $dados['atividade_id'] = Atividade::where('carimbo_data_hora', $importacao->carimbo_data_hora)
                                ->where('descricao', $importacao->atividade_descricao)
                                ->value('id');
                        }
                        break;
                    case 'Auxílio para Participação em Evento':
                        if(!empty($importacao->evento_nome)) {
                            $dados['evento_id'] = Evento::where('carimbo_data_hora', $importacao->carimbo_data_hora)
                                ->where('nome', $importacao->evento_nome)
                                ->value('id');
                        }
                        break;
                    case 'Aquisição de Material':
                        if(!empty($importacao->material_descricao)) {
                            $dados['material_id'] = Material::where('carimbo_data_hora', $importacao->carimbo_data_hora)
                                ->where('descricao', $importacao->material_descricao)
                                ->value('id');
                        }
                        break;
                    case 'Contratação de Serviço':
                        if(!empty($importacao->servico_tipo)) {
                            $dados['servico_id'] = Servico::where('carimbo_data_hora', $importacao->carimbo_data_hora)
                                ->where('titulo_artigo', $importacao->servico_titulo_artigo)
                                ->value('id');
                        }
                        break;
                    }
                    
                Solicitacao::firstOrCreate(['importacao_id' => $importacao->id], $dados);
            }

            return back()->with('success', 'Arquivo importado.');
        }
        catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];

            foreach ($failures as $failure) {
                $errorMessages[] = 'Linha ' . $failure->row() . ': ' . implode(', ', $failure->errors());
            }
    
            return back()->withErrors($errorMessages);
        }
        // catch (\Exception $e) {

        //     return back()->with('fail', 'Deu merda na importação: ' . $e->getMessage());
        // }
    }

    public function import_docentes(Request $request) {
        $request->validate([
            'file' => 'required|mimes:csv,txt',
        ]);

        Excel::import(new SolicitacoesDocentesImport, $request->file('file'));

        return back()->with('success', 'Arquivo importado.');
    }
}
