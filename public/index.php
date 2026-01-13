<?php
require_once __DIR__ . '/../src/MediaCalculator.php';

$aba_ativa = $_POST['tipo'] ?? 'simples';

$error = '';
$resultado = '';

// Média Simples
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $aba_ativa === 'simples') {
    $valores_input = trim($_POST['valores'] ?? '');
    if ($valores_input === '') {
        $error = 'Por favor, digite alguns valores!';
    } else {
        $valores = array_map('trim', explode(',', $valores_input));
        $media = MediaCalculator::mediaSimples($valores);
        if ($media === null) {
            $error = 'Nenhum valor numérico válido!';
        } else {
            $resultado = "Média Simples: <strong>" . number_format($media, 2, ',', '.') . "</strong><br>Valores usados: " . implode(', ', array_filter($valores, 'is_numeric'));
        }
    }
}

// Média Ponderada
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $aba_ativa === 'ponderada') {
    $pares_input = trim($_POST['valores_ponderada'] ?? '');
    if ($pares_input === '') {
        $error = 'Por favor, digite valores e pesos!';
    } else {
        $pares = [];
        foreach (explode(',', $pares_input) as $item) {
            if (strpos($item, ':') !== false) {
                [$v, $p] = array_map('trim', explode(':', $item));
                $pares[] = [$v, $p];
            }
        }
        $media = MediaCalculator::mediaPonderada($pares);
        if ($media === null) {
            $error = 'Nenhum par válido encontrado!';
        } else {
            $itens_validos = [];
            foreach ($pares as $p) {
                if (is_numeric($p[0]) && is_numeric($p[1]) && $p[1] > 0) {
                    $itens_validos[] = "{$p[0]}:{$p[1]}";
                }
            }
            $resultado = "Média Ponderada: <strong>" . number_format($media, 2, ',', '.') . "</strong><br>Itens usados: " . implode(', ', $itens_validos);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR" class="">
<head>
<meta charset="UTF-8">
<title>Calculadora de Médias</title>
<!-- Tailwind CDN -->
<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-gray-100 dark:bg-gray-900 transition-colors duration-300">
<div class="max-w-2xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md mt-10 transition-colors duration-300">
    <h1 class="text-3xl font-bold mb-6 text-gray-800 dark:text-gray-100">Calculadora de Médias</h1>

    <!-- Botão alternar tema -->
    <div class="flex justify-end mb-4">
        <button id="theme-toggle" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition flex items-center gap-2" type="button">
            <span id="theme-icon">🌙</span> Alternar Tema
        </button>
    </div>

    <!-- Abas -->
    <div class="flex mb-4 border-b border-gray-300 dark:border-gray-600 select-none">
        <div class="tab cursor-pointer px-4 py-2 <?= $aba_ativa=='simples'?'border-b-2 border-blue-500 font-bold text-blue-600 dark:text-blue-400':'text-gray-600 dark:text-gray-300' ?>" data-tab="simples">Média Simples</div>
        <div class="tab cursor-pointer px-4 py-2 <?= $aba_ativa=='ponderada'?'border-b-2 border-blue-500 font-bold text-blue-600 dark:text-blue-400':'text-gray-600 dark:text-gray-300' ?>" data-tab="ponderada">Média Ponderada</div>
    </div>

    <!-- Conteúdo das abas -->
    <div class="tab-content <?= $aba_ativa=='simples'?'block':'hidden' ?>" id="simples">
        <form method="post" class="space-y-4">
            <input type="hidden" name="tipo" value="simples">
            <textarea name="valores" class="w-full p-3 border border-gray-300 rounded dark:bg-gray-700 dark:text-gray-100 h-24 resize-none" placeholder="Digite valores separados por vírgula"><?= htmlspecialchars($_POST['valores'] ?? '') ?></textarea>
            <button type="submit" class="px-5 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition">Calcular Média Simples</button>
        </form>
    </div>

    <div class="tab-content <?= $aba_ativa=='ponderada'?'block':'hidden' ?>" id="ponderada">
        <form method="post" class="space-y-4">
            <input type="hidden" name="tipo" value="ponderada">
            <textarea name="valores_ponderada" class="w-full p-3 border border-gray-300 rounded dark:bg-gray-700 dark:text-gray-100 h-24 resize-none" placeholder="Formato: valor:peso, separados por vírgula"><?= htmlspecialchars($_POST['valores_ponderada'] ?? '') ?></textarea>
            <button type="submit" class="px-5 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition">Calcular Média Ponderada</button>
        </form>
    </div>

    <!-- Mensagens -->
    <?php if($error): ?>
        <div class="mt-4 text-red-600 font-semibold"><?= $error ?></div>
    <?php elseif($resultado): ?>
        <div class="mt-4 p-4 bg-gray-100 dark:bg-gray-700 rounded transition-colors duration-300"><?= $resultado ?></div>
    <?php endif; ?>
</div>

<script src="assets/js/theme.js"></script>
<script src="assets/js/tabs.js"></script>
</body>
</html>
