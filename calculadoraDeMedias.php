<?php
// Determina qual aba está ativa
$aba_ativa = isset($_POST['tipo']) ? $_POST['tipo'] : 'simples';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Calculadora de Médias</title>
<style>
    body { font-family: Arial, sans-serif; background: #f2f2f2; padding: 20px; }
    .container { max-width: 700px; margin: auto; background: #fff; padding: 20px; border-radius: 8px; }
    .tabs { display: flex; margin-bottom: 20px; }
    .tab { flex: 1; text-align: center; padding: 10px; background: #ddd; border-radius: 5px 5px 0 0; }
    .tab.active { background: #007bff; color: #fff; font-weight: bold; }
    .tab-content { display: none; padding: 15px; background: #f9f9f9; border-radius: 0 0 5px 5px; }
    .tab-content.active { display: block; }
    textarea { width: 100%; height: 80px; margin-bottom: 10px; }
    button { padding: 10px 15px; background: #007bff; color: #fff; border: none; cursor: pointer; }
    .result { margin-top: 20px; padding: 15px; background: #e9ecef; border-radius: 5px; }
    .error { color: red; font-weight: bold; }
</style>
</head>
<body>
<div class="container">
    <h1>Calculadora de Médias</h1>
    <div class="tabs">
        <div class="tab <?php echo ($aba_ativa=='simples')?'active':''; ?>">Média Simples</div>
        <div class="tab <?php echo ($aba_ativa=='ponderada')?'active':''; ?>">Média Ponderada</div>
    </div>

    <!-- Aba Média Simples -->
    <div class="tab-content <?php echo ($aba_ativa=='simples')?'active':''; ?>">
        <form method="post">
            <input type="hidden" name="tipo" value="simples">
            <label>Valores (separados por vírgula):</label>
            <textarea name="valores"><?php echo isset($_POST['valores']) ? htmlspecialchars($_POST['valores']) : ''; ?></textarea>
            <button type="submit">Calcular Média Simples</button>
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD']=='POST' && $aba_ativa=='simples') {
            if(empty(trim($_POST['valores']))) {
                echo "<div class='error'>Por favor, digite alguns valores!</div>";
            } else {
                $valores = array_map('trim', explode(',', $_POST['valores']));
                $num_validos = [];
                foreach($valores as $v){
                    if(is_numeric($v)) $num_validos[] = (float)$v;
                }
                if(empty($num_validos)){
                    echo "<div class='error'>Nenhum valor numérico válido!</div>";
                } else {
                    $media = array_sum($num_validos)/count($num_validos);
                    echo "<div class='result'>";
                    echo "Média Simples: <strong>".number_format($media,2,',','.')."</strong><br>";
                    echo "Valores usados: ".implode(', ',$num_validos);
                    echo "</div>";
                }
            }
        }
        ?>
    </div>

    <!-- Aba Média Ponderada -->
    <div class="tab-content <?php echo ($aba_ativa=='ponderada')?'active':''; ?>">
        <form method="post">
            <input type="hidden" name="tipo" value="ponderada">
            <label>Valores e Pesos (formato valor:peso, separados por vírgula):</label>
            <textarea name="valores_ponderada"><?php echo isset($_POST['valores_ponderada']) ? htmlspecialchars($_POST['valores_ponderada']) : ''; ?></textarea>
            <button type="submit">Calcular Média Ponderada</button>
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD']=='POST' && $aba_ativa=='ponderada') {
            if(empty(trim($_POST['valores_ponderada']))) {
                echo "<div class='error'>Por favor, digite valores e pesos!</div>";
            } else {
                $pares = array_map('trim', explode(',', $_POST['valores_ponderada']));
                $total_valor = 0; $total_peso = 0; $itens_validos = [];
                foreach($pares as $par){
                    if(strpos($par, ':')!==false){
                        list($v,$p) = explode(':',$par);
                        $v = trim($v); $p = trim($p);
                        if(is_numeric($v) && is_numeric($p) && $p>0){
                            $total_valor += $v*$p;
                            $total_peso += $p;
                            $itens_validos[] = "$v:$p";
                        }
                    }
                }
                if($total_peso==0){
                    echo "<div class='error'>Nenhum par válido encontrado!</div>";
                } else {
                    $media = $total_valor/$total_peso;
                    echo "<div class='result'>";
                    echo "Média Ponderada: <strong>".number_format($media,2,',','.')."</strong><br>";
                    echo "Itens usados: ".implode(', ',$itens_validos);
                    echo "</div>";
                }
            }
        }
        ?>
    </div>
</div>
</body>
</html>
