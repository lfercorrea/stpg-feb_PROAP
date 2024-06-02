<?php

namespace App\Imports;

use App\Models\ImportacoesDocentes;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Log;

class SolicitacoesDocentesImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Log::info('Dados lidos no CSV (docentes): ', $row);

        return new ImportacoesDocentes([
            'status' => $row[0],
            'carimbo_data_hora' => $row[1],
            'email' => $row[2],
            'programa' => $row[3],
            'categoria' => $row[4],
            'nome' => $row[5],
            'cpf' => $row[6],
            'rg' => $row[7],
            'rg_data_expedicao' => $row[8],
            'rg_orgao_expedidor' => $row[9],
            'nascimento' => $row[10],
            'endereco_completo' => $row[11],
            'telefone' => $row[12],
            'banco' => $row[13],
            'banco_agencia' => $row[14],
            'banco_conta' => $row[15],
            'tipo_solicitacao' => $row[16],
            'evento_nome' => $row[17],
            'evento_local' => $row[18],
            'evento_periodo' => $row[19],
            'evento_site_evento' => $row[20],
            'evento_titulo_trabalho' => $row[21],
            'evento_forma_participacao' => $row[22],
            'evento_valor_inscricao' => $row[23],
            'evento_valor_passagens' => $row[24],
            'evento_valor_diarias' => $row[25],
            'evento_justificativa' => $row[26],
            'evento_ja_solicitou_recurso' => $row[27],
            'evento_artigo_copia' => $row[28],
            'evento_artigo_aceite' => $row[29],
            'evento_orcamento_passagens' => $row[30],
            'material_descricao' => $row[31],
            'material_valor' => $row[32],
            'material_justificativa' => $row[33],
            'material_ja_solicitou_recurso' => $row[34],
            'material_orcamento' => $row[35],
            'servico_tipo' => $row[36],
            'servico_titulo_artigo' => $row[37],
            'servico_valor' => $row[38],
            'servico_justificativa' => $row[39],
            'servico_artigo_a_traduzir' => $row[40],
            'servico_orcamento' => $row[41],
            'manutencao_descricao' => $row[42],
            'manutencao_numero_patrimonio' => $row[43],
            'manutencao_valor' => $row[44],
            'manutencao_justificativa' => $row[45],
            'manutencao_orcamento' => $row[46],
            'atividade_descricao' => $row[47],
            'atividade_local' => $row[48],
            'atividade_periodo' => $row[49],
            'atividade_valor_diarias' => $row[50],
            'atividade_valor_passagens' => $row[51],
            'atividade_justificativa' => $row[52],
            'atividade_orcamento_passagens' => $row[53],
            'atividade_carta_convite' => $row[54],
            'outros_servicos_descricao' => $row[55],
            'outros_servicos_valor' => $row[56],
            'outros_servicos_justificativa' => $row[57],
            'outros_servicos_orcamento' => $row[58],
        ]);
    }
}
