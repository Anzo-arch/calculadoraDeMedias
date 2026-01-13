<?php
class MediaCalculator {

    public static function mediaSimples(array $valores): ?float {
        $num_validos = array_filter($valores, 'is_numeric');
        if (empty($num_validos)) return null;
        return array_sum($num_validos) / count($num_validos);
    }

    public static function mediaPonderada(array $pares): ?float {
        $total_valor = 0;
        $total_peso = 0;

        foreach ($pares as [$valor, $peso]) {
            if (is_numeric($valor) && is_numeric($peso) && $peso > 0) {
                $total_valor += $valor * $peso;
                $total_peso += $peso;
            }
        }

        if ($total_peso === 0) return null;
        return $total_valor / $total_peso;
    }
}
