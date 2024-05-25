<?php

namespace App\Imports;

use App\Models\ImportacoesDiscentes;
use Maatwebsite\Excel\Concerns\ToModel;

class SolicitacoesDiscentesImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new ImportacoesDiscentes([
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
            'evento_parecer_orientador' => $row[30],
            'evento_orcamento_passagens' => $row[31],
            'material_descricao' => $row[32],
            'material_valor' => $row[33],
            'material_justificativa' => $row[34],
            'material_ja_solicitou_recurso' => $row[35],
            'material_orcamento' => $row[36],
            'material_parecer_orientador' => $row[37],
            'servico_tipo' => $row[38],
            'servico_titulo_artigo' => $row[39],
            'servico_valor' => $row[40],
            'servico_justificativa' => $row[41],
            'servico_artigo_a_traduzir' => $row[42],
            'servico_orcamento' => $row[43],
            'servico_parecer_orientador' => $row[44],
            'atividade_descricao' => $row[45],
            'atividade_local' => $row[46],
            'atividade_periodo' => $row[47],
            'atividade_valor_diarias' => $row[48],
            'atividade_valor_passagens' => $row[49],
            'atividade_justificativa' => $row[50],
            'atividade_carta_convite' => $row[51],
            'atividade_parecer_orientador' => $row[52],
            'atividade_orcamento_passagens' => $row[53],
            'atividade_nome_orientador' => $row[54],
        ]);
    }
}
