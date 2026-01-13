<?php
class MediaCalculator {

    // Calcula média simples
    public static function simples(string $valores): float {
        $valoresArray = array_map('trim', explode(',', $valores));
        $numeros = [];

        foreach($valoresArray as $v){
            if(is_numeric($v)) $numeros[] = (float)$v;
        }

        if(empty($numeros)){
            throw new InvalidArgumentException('Nenhum valor numérico válido!');
        }

        return array_sum($numeros) / count($numeros);
    }

    // Calcula média ponderada
    public static function ponderada(string $pares): float {
        $paresArray = array_map('trim', explode(',', $pares));
        $totalValor = 0;
        $totalPeso = 0;

        foreach($paresArray as $par){
            if(strpos($par, ':') !== false){
                list($v, $p) = explode(':', $par);
                $v = trim($v);
                $p = trim($p);
                if(is_numeric($v) && is_numeric($p) && $p > 0){
                    $totalValor += $v * $p;
                    $totalPeso += $p;
                }
            }
        }

        if($totalPeso === 0){
            throw new InvalidArgumentException('Nenhum par válido encontrado!');
        }

        return $totalValor / $totalPeso;
    }
}
