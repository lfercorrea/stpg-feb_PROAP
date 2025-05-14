<?php

namespace App\Imports;

use App\Models\ImportacoesDocentes;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class SolicitacoesDocentesImport implements ToCollection
{
    protected array $rows;
    protected array $invalidEmails = [];

    public function __construct(array $rows)
    {
        $this->rows = $rows;
    }

    public function collection(Collection $collection)
    {
        foreach ($this->rows as $row) {
            $email = str_replace(' ', '', $row[2]);

            if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/@unesp\.br$/', $email)) {
                $this->invalidEmails[] = "(" . $row[1] . ") " . $email;
                continue;
            }

            ImportacoesDocentes::create([
                'status' => $row[0],
                'carimbo_data_hora' => $row[1],
                'email' => $email,
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
        
        if (!empty($this->invalidEmails)) {
            $errorMessages = implode(', ', $this->invalidEmails);
            return redirect()->back()->with('fail', "Os emails válidos foram importados. No entanto, foram encontrados emails inválidos (ou pelo menos, deveriam ser emails) e eles não podem ser importados. As linhas com estes emails ***precisam*** ser removidas da planilha, caso contrário, todas as próximas importações irão falhar. Lembre-se que os emails precisam ser @unesp.br. Dados de referência para localizar os emails inválidos: " . $errorMessages . '.');
        }
    }
}
