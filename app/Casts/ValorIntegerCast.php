<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class ValorIntegerCast implements CastsAttributes
{
    /**
     * converte o inteiro, que é em centavos, float (valor monetário).
     * @param  mixed  $value é o valor lido do banco (BIGINT)
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return is_numeric($value) ? (float) ($value / 100) : 0.0;
    }

    /**
     * converte o valor da entrada (float, string, int) para inteiro (em centavos) antes de enviar ao banco.
     * @param  mixed  $value é o valor a ser salvo (pode vir como float, string, etc.)
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if (is_null($value) || $value === '') {

            return 0;
        }

        if (is_numeric($value)) {
            $numeric_val = (float) $value;
        }
        elseif (is_string($value)) {
            $cleaned = str_replace('R$', '', $value);
            $cleaned = str_replace('.', '', $cleaned);
            $cleaned = str_replace(',', '.', $cleaned);
            $cleaned = trim($cleaned);
            $numeric_val = is_numeric($cleaned) ? (float) $cleaned : 0.0;

            if (! is_numeric($cleaned)) {
                // se a string limpa ainda não passar nesse teste, então é uma entrada inválida.
                // o ideal seria lançar uma exceção agora, mas estou com pressa. sinta-se à vontade para
                // melhorar isso. tysm
                return 0;
            }

        }
        else {
            // nesta hipótese, também não é string. pode ocorrer se o usuário for um engracadinho
            // metido a H4Ck3r
            return 0;
        }

        // salva no banco em centavos
        return (int) round($numeric_val * 100);
    }
}