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
use App\Models\TraducaoArtigo;
use App\Models\Manutencao;
use App\Models\OutroServico;
use App\Models\ImportacoesDiscentes;
use App\Models\ImportacoesDocentes;
use App\Models\Programa;
use App\Models\DocenteCategoria;
use App\Models\ProgramaCategoria;
use App\Models\Solicitacao;
use App\Models\ServicoTipo;
use App\Models\SolicitacaoTipo;
use App\Models\Solicitante;

class CsvImportController extends Controller
{
    public function import_menu(){
        return view('importar', [
            //
        ]);
    }
    public function show_import_discentes_form() {
        return view('import_form', [
            'solicitante' => 'discentes',
        ]);
    }

    public function show_import_docentes_form() {
        return view('import_form', [
            'solicitante' => 'docentes',
        ]);
    }

    public function show() {
        return view('importacoes');
    }

    public function import_discentes(Request $request) {
        $request->validate([
            'file' => 'required|mimes:csv,txt',
        ], [
            'file.required' => 'Você esqueceu de escolher a planilha',
            'file.mimes' => 'A planilha deve estar no formato CSV',
        ]);

        try{
            /**
             * realimenta a tabela importacoes
             */
            ImportacoesDiscentes::truncate();
            Excel::import(new SolicitacoesDiscentesImport, $request->file('file'));
            ImportacoesDiscentes::destroy(1); // passa o rodo na linha do csv que contem os cabeçalhos
            ImportacoesDiscentes::whereNull('tipo_solicitante')->update(['tipo_solicitante' => 'Discente']);
            $importacoes_discentes = ImportacoesDiscentes::all();

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
            foreach($importacoes_discentes as $discente) {
                Solicitante::updateOrCreate([
                    'email' => $discente->email,
                ], [
                    'email' => $discente->email,
                    'nome' => $discente->nome,
                    'tipo_solicitante' => $discente->tipo_solicitante,
                    'cpf' => $discente->cpf,
                    'rg' => $discente->rg,
                    'rg_data_expedicao' => $discente->rg_data_expedicao,
                    'rg_orgao_expedidor' => $discente->rg_orgao_expedidor,
                    'nascimento' => $discente->nascimento,
                    'endereco_completo' => $discente->endereco_completo,
                    'telefone' => $discente->telefone,
                    'banco' => $discente->banco,
                    'banco_agencia' => $discente->banco_agencia,
                    'banco_conta' => $discente->banco_conta,
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
            foreach($importacoes_discentes as $importacao_discentes) {
                switch($importacao_discentes->tipo_solicitacao) {
                    case 'Auxílio para Participação em Evento':
                        if(!empty($importacao_discentes->evento_nome)) {
                            Evento::firstOrCreate([
                                'importacao_discentes_id' => $importacao_discentes->id,
                            ], [
                                'nome' => $importacao_discentes->evento_nome,
                                'local' => $importacao_discentes->evento_local,
                                'periodo' => $importacao_discentes->evento_periodo,
                                'site_evento' => $importacao_discentes->evento_site_evento,
                                'titulo_trabalho' => $importacao_discentes->evento_titulo_trabalho,
                                'forma_participacao' => $importacao_discentes->evento_forma_participacao,
                                'valor_inscricao' => $importacao_discentes->evento_valor_inscricao,
                                'valor_passagens' => $importacao_discentes->evento_valor_passagens,
                                'valor_diarias' => $importacao_discentes->evento_valor_diarias,
                                'justificativa' => $importacao_discentes->evento_justificativa,
                                'ja_solicitou_recurso' => $importacao_discentes->evento_ja_solicitou_recurso,
                                'artigo_copia' => $importacao_discentes->evento_artigo_copia,
                                'artigo_aceite' => $importacao_discentes->evento_artigo_aceite,
                                'parecer_orientador' => $importacao_discentes->evento_parecer_orientador,
                                'orcamento_passagens' => $importacao_discentes->evento_orcamento_passagens,
                                'carimbo_data_hora' => $importacao_discentes->carimbo_data_hora,
                                'importacao_discentes_id' => $importacao_discentes->id,
                            ]);
                        }
                        break;
                    case 'Auxílio para Pesquisa de Campo':
                        if(!empty($importacao_discentes->atividade_descricao)) {
                            Atividade::firstOrCreate([
                                'importacao_discentes_id' => $importacao_discentes->id,
                            ], [
                                'descricao' => $importacao_discentes->atividade_descricao,
                                'local' => $importacao_discentes->atividade_local,
                                'periodo' => $importacao_discentes->atividade_periodo,
                                'valor_diarias' => $importacao_discentes->atividade_valor_diarias,
                                'valor_passagens' => $importacao_discentes->atividade_valor_passagens,
                                'justificativa' => $importacao_discentes->atividade_justificativa,
                                'carta_convite' => $importacao_discentes->atividade_carta_convite,
                                'parecer_orientador' => $importacao_discentes->atividade_parecer_orientador,
                                'orcamento_passagens' => $importacao_discentes->atividade_orcamento_passagens,
                                'carimbo_data_hora' => $importacao_discentes->carimbo_data_hora,
                                'importacao_discentes_id' => $importacao_discentes->id,
                            ]);
                        }
                        break;
                    case 'Aquisição de Material':
                        if(!empty($importacao_discentes->material_descricao)) {
                            Material::firstOrCreate([
                                'importacao_discentes_id' => $importacao_discentes->id,
                            ], [
                                'descricao' => $importacao_discentes->material_descricao,
                                'valor' => $importacao_discentes->material_valor,
                                'justificativa' => $importacao_discentes->material_justificativa,
                                'ja_solicitou_recurso' => $importacao_discentes->material_ja_solicitou_recurso,
                                'orcamento' => $importacao_discentes->material_orcamento,
                                'parecer_orientador' => $importacao_discentes->material_parecer_orientador,
                                'carimbo_data_hora' => $importacao_discentes->carimbo_data_hora,
                                'importacao_discentes_id' => $importacao_discentes->id,
                            ]);
                        }
                        break;
                    case 'Contratação de Serviço':
                        if(!empty($importacao_discentes->servico_tipo)) {
                            switch($importacao_discentes->servico_tipo) {
                                case 'Tradução de Artigo':
                                    TraducaoArtigo::firstOrCreate([
                                        'importacao_discentes_id' => $importacao_discentes->id,
                                    ], [
                                        'titulo_artigo' => $importacao_discentes->servico_titulo_artigo,
                                        'valor' => $importacao_discentes->servico_valor,
                                        'justificativa' => $importacao_discentes->servico_justificativa,
                                        'artigo_a_traduzir' => $importacao_discentes->servico_artigo_a_traduzir,
                                        'orcamento' => $importacao_discentes->servico_orcamento,
                                        'parecer_orientador' => $importacao_discentes->servico_parecer_orientador,
                                        'carimbo_data_hora' => $importacao_discentes->carimbo_data_hora,
                                        'importacao_discentes_id' => $importacao_discentes->id,
                                    ]);
                                    break;
                                // esse case é morto, pois discentes só pedem tradução de artigo. mesmo assim, vou deixar aqui
                                case 'Manutenção e Conservação de Máquinas e Equipamentos':
                                    Manutencao::firstOrCreate([
                                        'importacao_discentes_id' => $importacao_discentes->id,
                                    ], [
                                        'descricao' => $importacao_discentes->manutencao_descricao,
                                        'numero_patrimonio' => $importacao_discentes->manutencao_numero_patrimonio,
                                        'valor' => $importacao_discentes->manutencao_valor,
                                        'justificativa' => $importacao_discentes->manutencao_justificativa,
                                        'orcamento' => $importacao_discentes->manutencao_orcamento,
                                        'carimbo_data_hora' => $importacao_discentes->carimbo_data_hora,
                                        'importacao_discentes_id' => $importacao_discentes->id,
                                    ]);
                                    break;
                                // esse case é morto, pois discentes só pedem tradução de artigo. mesmo assim, vou deixar aqui
                                case 'Outros Serviços':
                                    OutroServico::firstOrCreate([
                                        'importacao_discentes_id' => $importacao_discentes->id,
                                    ], [
                                        'descricao' => $importacao_discentes->outros_servicos_descricao,
                                        'valor' => $importacao_discentes->outros_servicos_valor,
                                        'justificativa' => $importacao_discentes->outros_servicos_justificativa,
                                        'orcamento' => $importacao_discentes->outros_servicos_orcamento,
                                        'carimbo_data_hora' => $importacao_discentes->carimbo_data_hora,
                                        'importacao_discentes_id' => $importacao_discentes->id,
                                    ]);
                                    break;
                            }
                        }
                        break;
                }
            }

            /**
             * finalmente, a tabela de solicitacoes que também fornecerá os relacionamentos
             */
            foreach($importacoes_discentes as $importacao_discentes) {
                $dados_discentes = [
                    'solicitante_id' => Solicitante::where('email', $importacao_discentes->email)->value('id'),
                    'programa_id' => Programa::where('nome', $importacao_discentes->programa)->value('id'),
                    'programa_categoria_id' => ProgramaCategoria::where('nome', $importacao_discentes->categoria)->value('id'),
                    'tipo_solicitacao_id' => SolicitacaoTipo::where('nome', $importacao_discentes->tipo_solicitacao)->value('id'),
                    'nome_do_orientador' => $importacao_discentes->nome_do_orientador,
                    'status_id' => 6,
                    'observacao' => $importacao_discentes->status,
                    'carimbo_data_hora' => $importacao_discentes->carimbo_data_hora,
                    'importacao_discentes_id' => $importacao_discentes->id,
                ];

                switch($importacao_discentes->tipo_solicitacao) {
                    case 'Auxílio para Pesquisa de Campo':
                        if(!empty($importacao_discentes->atividade_descricao)) {
                            $dados_discentes['atividade_id'] = Atividade::where('carimbo_data_hora', $importacao_discentes->carimbo_data_hora)
                                ->where('descricao', $importacao_discentes->atividade_descricao)
                                ->value('id');
                        }
                        break;
                    case 'Auxílio para Participação em Evento':
                        if(!empty($importacao_discentes->evento_nome)) {
                            $dados_discentes['evento_id'] = Evento::where('carimbo_data_hora', $importacao_discentes->carimbo_data_hora)
                                ->where('nome', $importacao_discentes->evento_nome)
                                ->value('id');
                        }
                        break;
                    case 'Aquisição de Material':
                        if(!empty($importacao_discentes->material_descricao)) {
                            $dados_discentes['material_id'] = Material::where('carimbo_data_hora', $importacao_discentes->carimbo_data_hora)
                                ->where('descricao', $importacao_discentes->material_descricao)
                                ->value('id');
                        }
                        break;
                    case 'Contratação de Serviço':
                        switch($importacao_discentes->servico_tipo) {
                            case 'Tradução de Artigo':
                                if(!empty($importacao_discentes->servico_titulo_artigo)) {
                                    $dados_discentes['traducao_artigo_id'] = TraducaoArtigo::where('carimbo_data_hora', $importacao_discentes->carimbo_data_hora)
                                        ->where('titulo_artigo', $importacao_discentes->servico_titulo_artigo)
                                        ->value('id');
                                }
                                break;
                            // esse case é morto, pois discentes só pedem tradução de artigo. mesmo assim, vou deixar aqui
                            case 'Manutenção e Conservação de Máquinas e Equipamentos':
                                if(!empty($importacao_discentes->manutencao_descricao)) {
                                    $dados_discentes['manutencao_id'] = Manutencao::where('carimbo_data_hora', $importacao_discentes->carimbo_data_hora)
                                        ->where('descricao', $importacao_discentes->manutencao_descricao)
                                        ->value('id');
                                }
                                break;
                            // esse case é morto, pois discentes só pedem tradução de artigo. mesmo assim, vou deixar aqui
                            case 'Outros Serviços':
                                if(!empty($importacao_discentes->outros_servicos_descricao)) {
                                    $dados_discentes['outro_servico_id'] = OutroServico::where('carimbo_data_hora', $importacao_discentes->carimbo_data_hora)
                                        ->where('descricao', $importacao_discentes->outros_servicos_descricao)
                                        ->value('id');
                                }
                                break;
                        }
                    }
                    
                Solicitacao::firstOrCreate(['importacao_discentes_id' => $importacao_discentes->id], $dados_discentes);
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
        ], [
            'file.required' => 'Você esqueceu de escolher a planilha',
            'file.mimes' => 'A planilha deve estar no formato CSV',
        ]);

        try{
            /**
             * realimenta a tabela importacoes
             */
            ImportacoesDocentes::truncate();
            Excel::import(new SolicitacoesDocentesImport, $request->file('file'));
            ImportacoesDocentes::destroy(1); // passa o rodo na linha do csv que contem os cabeçalhos
            $importacoes_docentes = ImportacoesDocentes::all();

            /**
             * realimenta a tabela de programas conforme a planilha
             */
            $programas_importados_distintos = DB::table('importacoes_docentes')
                ->select('programa')
                ->distinct()
                ->get();
                
            foreach($programas_importados_distintos as $distinto) {
                if(!empty($distinto->programa)) {
                    Programa::firstOrCreate([
                        'nome' => $distinto->programa,
                    ]);
                }
            }

            /**
             * realimenta a tabela de programa_categorias conforme a planilha
             */
            $categorias_importadas_distintas = DB::table('importacoes_docentes')
                ->select('categoria')
                ->distinct()
                ->get();

            foreach($categorias_importadas_distintas as $distinta) {
                if(!empty($distinta->categoria)) {
                    DocenteCategoria::firstOrCreate([
                        'categoria' => $distinta->categoria,
                    ]);
                }
            }

            /**
             * realimenta a tabela de solicitantes, ignorando duplicidades
             * de acordo com email unico
             */
            foreach($importacoes_docentes as $docente) {
                Solicitante::updateOrCreate([
                    'email' => $docente->email,
                ], [
                    'email' => $docente->email,
                    'nome' => $docente->nome,
                    'tipo_solicitante' => $docente->categoria,
                    'cpf' => $docente->cpf,
                    'rg' => $docente->rg,
                    'rg_data_expedicao' => $docente->rg_data_expedicao,
                    'rg_orgao_expedidor' => $docente->rg_orgao_expedidor,
                    'nascimento' => $docente->nascimento,
                    'endereco_completo' => $docente->endereco_completo,
                    'telefone' => $docente->telefone,
                    'banco' => $docente->banco,
                    'banco_agencia' => $docente->banco_agencia,
                    'banco_conta' => $docente->banco_conta,
                ]);
            }

            /**
             * realimenta a tabela de tipos de solicitacao e injeta na tabela de categorias
             */
            $tipos_solicitacao_distintos = DB::table('importacoes_docentes')
                ->select('tipo_solicitacao')
                ->distinct('tipo_solicitacao')
                ->get();
                
            foreach($tipos_solicitacao_distintos as $distinto) {
                if(!empty($distinto->tipo_solicitacao)) {
                    SolicitacaoTipo::firstOrCreate([
                        'nome' => $distinto->tipo_solicitacao,
                    ]);
                }
            }

            /**
             * realimenta a tabela de tipos de serviço e injeta na respectiva tabela
             */
            $tipos_servico_distintos = DB::table('importacoes_docentes')
                ->select('servico_tipo')
                ->distinct('servico_tipo')
                ->get();
                
            foreach($tipos_servico_distintos as $distinto) {
                if(!empty($distinto->servico_tipo)) {
                    ServicoTipo::firstOrCreate([
                        'nome' => $distinto->servico_tipo,
                    ]);
                }
            }

            /**
             * segmentar conforme tipo de solicitacao
             */
            foreach($importacoes_docentes as $importacao_docentes) {
                switch($importacao_docentes->tipo_solicitacao) {
                    case 'Auxílio para Participação em Evento':
                        if(!empty($importacao_docentes->evento_nome)) {
                            Evento::firstOrCreate([
                                'importacao_docentes_id' => $importacao_docentes->id,
                            ], [
                                'nome' => $importacao_docentes->evento_nome,
                                'local' => $importacao_docentes->evento_local,
                                'periodo' => $importacao_docentes->evento_periodo,
                                'site_evento' => $importacao_docentes->evento_site_evento,
                                'titulo_trabalho' => $importacao_docentes->evento_titulo_trabalho,
                                'forma_participacao' => $importacao_docentes->evento_forma_participacao,
                                'valor_inscricao' => $importacao_docentes->evento_valor_inscricao,
                                'valor_passagens' => $importacao_docentes->evento_valor_passagens,
                                'valor_diarias' => $importacao_docentes->evento_valor_diarias,
                                'justificativa' => $importacao_docentes->evento_justificativa,
                                'ja_solicitou_recurso' => $importacao_docentes->evento_ja_solicitou_recurso,
                                'artigo_copia' => $importacao_docentes->evento_artigo_copia,
                                'artigo_aceite' => $importacao_docentes->evento_artigo_aceite,
                                'orcamento_passagens' => $importacao_docentes->evento_orcamento_passagens,
                                'carimbo_data_hora' => $importacao_docentes->carimbo_data_hora,
                                'importacao_docentes_id' => $importacao_docentes->id,
                            ]);
                        }
                        break;
                    case 'Auxílio para Pesquisa de Campo':
                        if(!empty($importacao_docentes->atividade_descricao)) {
                            Atividade::firstOrCreate([
                                'importacao_docentes_id' => $importacao_docentes->id,
                            ], [
                                'descricao' => $importacao_docentes->atividade_descricao,
                                'local' => $importacao_docentes->atividade_local,
                                'periodo' => $importacao_docentes->atividade_periodo,
                                'valor_diarias' => $importacao_docentes->atividade_valor_diarias,
                                'valor_passagens' => $importacao_docentes->atividade_valor_passagens,
                                'justificativa' => $importacao_docentes->atividade_justificativa,
                                'carta_convite' => $importacao_docentes->atividade_carta_convite,
                                'orcamento_passagens' => $importacao_docentes->atividade_orcamento_passagens,
                                'carimbo_data_hora' => $importacao_docentes->carimbo_data_hora,
                                'importacao_docentes_id' => $importacao_docentes->id,
                            ]);
                        }
                        break;
                    case 'Aquisição de Material':
                        if(!empty($importacao_docentes->material_descricao)) {
                            Material::firstOrCreate([
                                'importacao_docentes_id' => $importacao_docentes->id,
                            ], [
                                'descricao' => $importacao_docentes->material_descricao,
                                'valor' => $importacao_docentes->material_valor,
                                'justificativa' => $importacao_docentes->material_justificativa,
                                'ja_solicitou_recurso' => $importacao_docentes->material_ja_solicitou_recurso,
                                'orcamento' => $importacao_docentes->material_orcamento,
                                'carimbo_data_hora' => $importacao_docentes->carimbo_data_hora,
                                'importacao_docentes_id' => $importacao_docentes->id,
                            ]);
                        }
                        break;
                    case 'Contratação de Serviço':
                        if(!empty($importacao_docentes->servico_tipo)) {

                            switch($importacao_docentes->servico_tipo) {
                                case 'Tradução de Artigo':
                                    TraducaoArtigo::firstOrCreate([
                                        'importacao_docentes_id' => $importacao_docentes->id,
                                    ], [
                                        'titulo_artigo' => $importacao_docentes->servico_titulo_artigo,
                                        'valor' => $importacao_docentes->servico_valor,
                                        'justificativa' => $importacao_docentes->servico_justificativa,
                                        'artigo_a_traduzir' => $importacao_docentes->servico_artigo_a_traduzir,
                                        'orcamento' => $importacao_docentes->servico_orcamento,
                                        'carimbo_data_hora' => $importacao_docentes->carimbo_data_hora,
                                        'importacao_docentes_id' => $importacao_docentes->id,
                                    ]);
                                    break;
                                case 'Manutenção e Conservação de Máquinas e Equipamentos':
                                    Manutencao::firstOrCreate([
                                        'importacao_docentes_id' => $importacao_docentes->id,
                                    ], [
                                        'descricao' => $importacao_docentes->manutencao_descricao,
                                        'numero_patrimonio' => $importacao_docentes->manutencao_numero_patrimonio,
                                        'valor' => $importacao_docentes->manutencao_valor,
                                        'justificativa' => $importacao_docentes->manutencao_justificativa,
                                        'orcamento' => $importacao_docentes->manutencao_orcamento,
                                        'carimbo_data_hora' => $importacao_docentes->carimbo_data_hora,
                                        'importacao_docentes_id' => $importacao_docentes->id,
                                    ]);
                                    break;
                                case 'Outros Serviços':
                                    OutroServico::firstOrCreate([
                                        'importacao_docentes_id' => $importacao_docentes->id,
                                    ], [
                                        'descricao' => $importacao_docentes->outros_servicos_descricao,
                                        'valor' => $importacao_docentes->outros_servicos_valor,
                                        'justificativa' => $importacao_docentes->outros_servicos_justificativa,
                                        'orcamento' => $importacao_docentes->outros_servicos_orcamento,
                                        'carimbo_data_hora' => $importacao_docentes->carimbo_data_hora,
                                        'importacao_docentes_id' => $importacao_docentes->id,
                                    ]);
                                    break;
                            }
                        }
                        break;
                }
            }

            /**
             * finalmente, a tabela de solicitacoes que também fornecerá os relacionamentos
             */
            foreach($importacoes_docentes as $importacao_docentes) {
                $dados_docentes = [
                    'solicitante_id' => Solicitante::where('email', $importacao_docentes->email)->value('id'),
                    'programa_id' => Programa::where('nome', $importacao_docentes->programa)->value('id'),
                    'programa_categoria_id' => ProgramaCategoria::where('nome', $importacao_docentes->categoria)->value('id'),
                    'tipo_solicitacao_id' => SolicitacaoTipo::where('nome', $importacao_docentes->tipo_solicitacao)->value('id'),
                    'nome_do_orientador' => $importacao_docentes->nome_do_orientador,
                    'status_id' => 6,
                    'observacao' => $importacao_docentes->status,
                    'carimbo_data_hora' => $importacao_docentes->carimbo_data_hora,
                    'importacao_docentes_id' => $importacao_docentes->id,
                ];

                switch($importacao_docentes->tipo_solicitacao) {
                    case 'Auxílio para Pesquisa de Campo':
                        if(!empty($importacao_docentes->atividade_descricao)) {
                            $dados_docentes['atividade_id'] = Atividade::where('carimbo_data_hora', $importacao_docentes->carimbo_data_hora)
                                ->where('descricao', $importacao_docentes->atividade_descricao)
                                ->value('id');
                        }
                        break;
                    case 'Auxílio para Participação em Evento':
                        if(!empty($importacao_docentes->evento_nome)) {
                            $dados_docentes['evento_id'] = Evento::where('carimbo_data_hora', $importacao_docentes->carimbo_data_hora)
                                ->where('nome', $importacao_docentes->evento_nome)
                                ->value('id');
                        }
                        break;
                    case 'Aquisição de Material':
                        if(!empty($importacao_docentes->material_descricao)) {
                            $dados_docentes['material_id'] = Material::where('carimbo_data_hora', $importacao_docentes->carimbo_data_hora)
                                ->where('descricao', $importacao_docentes->material_descricao)
                                ->value('id');
                        }
                        break;
                    case 'Contratação de Serviço':
                        switch($importacao_docentes->servico_tipo) {
                            case 'Tradução de Artigo':
                                if(!empty($importacao_docentes->servico_titulo_artigo)) {
                                    $dados_docentes['traducao_artigo_id'] = TraducaoArtigo::where('carimbo_data_hora', $importacao_docentes->carimbo_data_hora)
                                        ->where('titulo_artigo', $importacao_docentes->servico_titulo_artigo)
                                        ->value('id');
                                }
                                break;
                            case 'Manutenção e Conservação de Máquinas e Equipamentos':
                                if(!empty($importacao_docentes->manutencao_descricao)) {
                                    $dados_docentes['manutencao_id'] = Manutencao::where('carimbo_data_hora', $importacao_docentes->carimbo_data_hora)
                                        ->where('descricao', $importacao_docentes->manutencao_descricao)
                                        ->value('id');
                                }
                                break;
                            case 'Outros Serviços':
                                if(!empty($importacao_docentes->outros_servicos_descricao)) {
                                    $dados_docentes['outro_servico_id'] = OutroServico::where('carimbo_data_hora', $importacao_docentes->carimbo_data_hora)
                                        ->where('descricao', $importacao_docentes->outros_servicos_descricao)
                                        ->value('id');
                                }
                                break;
                        }
                    }
                    
                Solicitacao::firstOrCreate(['importacao_docentes_id' => $importacao_docentes->id], $dados_docentes);
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
}
