<?php
require_once dirname(__DIR__) . '/src/MediaCalculator.php';

// Determina qual aba está ativa
$aba_ativa = $_POST['tipo'] ?? 'simples';

$resultado = '';
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if ($aba_ativa === 'simples') {
            $media = MediaCalculator::simples($_POST['valores'] ?? '');
            $valores = array_map('trim', explode(',', $_POST['valores']));
            $num_validos = array_filter($valores, 'is_numeric');
            $resultado = "Média Simples: <strong>" . number_format($media,2,',','.') . "</strong><br>" .
                         "Valores usados: " . implode(', ', $num_validos);
        } elseif ($aba_ativa === 'ponderada') {
            $media = MediaCalculator::ponderada($_POST['valores_ponderada'] ?? '');
            $pares = array_map('trim', explode(',', $_POST['valores_ponderada']));
            $itens_validos = [];
            foreach($pares as $par){
                if(strpos($par, ':')!==false){
                    list($v,$p) = explode(':',$par);
                    if(is_numeric($v) && is_numeric($p) && $p>0){
                        $itens_validos[] = "$v:$p";
                    }
                }
            }
            $resultado = "Média Ponderada: <strong>" . number_format($media,2,',','.') . "</strong><br>" .
                         "Itens usados: " . implode(', ', $itens_validos);
        }
    } catch (InvalidArgumentException $e) {
        $erro = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Calculadora de Médias</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="container">
    <button id="theme-toggle" title="Alternar Tema">🌙/☀️</button>
    <h1>Calculadora de Médias</h1>
    <div class="tabs">
        <div class="tab <?= ($aba_ativa=='simples')?'active':''; ?>">Média Simples</div>
        <div class="tab <?= ($aba_ativa=='ponderada')?'active':''; ?>">Média Ponderada</div>
    </div>

    <!-- Aba Média Simples -->
    <div class="tab-content <?= ($aba_ativa=='simples')?'active':''; ?>">
        <form method="post">
            <input type="hidden" name="tipo" value="simples">
            <label>Valores (separados por vírgula):</label>
            <textarea name="valores"><?= htmlspecialchars($_POST['valores'] ?? '') ?></textarea>
            <button type="submit">Calcular Média Simples</button>
        </form>
    </div>

    <!-- Aba Média Ponderada -->
    <div class="tab-content <?= ($aba_ativa=='ponderada')?'active':''; ?>">
        <form method="post">
            <input type="hidden" name="tipo" value="ponderada">
            <label>Valores e Pesos (formato valor:peso, separados por vírgula):</label>
            <textarea name="valores_ponderada"><?= htmlspecialchars($_POST['valores_ponderada'] ?? '') ?></textarea>
            <button type="submit">Calcular Média Ponderada</button>
        </form>
    </div>

    <?php if($erro): ?>
        <div class="error"><?= $erro ?></div>
    <?php elseif($resultado): ?>
        <div class="result"><?= $resultado ?></div>
    <?php endif; ?>
</div>

<script src="assets/js/tabs.js"></script>
<script src="assets/js/theme.js"></script>
</body>
</html>
