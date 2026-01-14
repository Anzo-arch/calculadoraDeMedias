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
            $resultado = "Média Simples: <strong>" .
                number_format($media, 2, ',', '.') .
                "</strong><br>Valores usados: " .
                implode(', ', array_filter($valores, 'is_numeric'));
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
            $resultado = "Média Ponderada: <strong>" .
                number_format($media, 2, ',', '.') .
                "</strong><br>Itens usados: " .
                implode(', ', $itens_validos);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR" class="transition-colors duration-500">
<head>
<meta charset="UTF-8">
<title>Calculadora de Médias</title>

<!-- 🔥 Previne FOUC (tema correto antes de renderizar) -->
<script>
  if (
    localStorage.theme === 'dark' ||
    (!('theme' in localStorage) &&
      window.matchMedia('(prefers-color-scheme: dark)').matches)
  ) {
    document.documentElement.classList.add('dark');
  } else {
    document.documentElement.classList.remove('dark');
  }
</script>

<!-- Tailwind via CDN -->
<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

<link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="bg-gray-100 dark:bg-gray-900 transition-colors duration-500">

<div class="max-w-2xl mx-auto mt-12 p-8 bg-white dark:bg-gray-800 rounded-xl shadow-xl transition-colors duration-500">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-extrabold text-gray-800 dark:text-gray-100">
            Calculadora de Médias
        </h1>

        <!-- Botão Tema -->
        <button id="theme-toggle"
            class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            <span id="theme-icon">🌙</span>
        </button>
    </div>

    <!-- Abas -->
    <div class="flex border-b border-gray-300 dark:border-gray-600 mb-6 select-none">
        <div class="tab px-5 py-2 cursor-pointer <?= $aba_ativa==='simples' ? 'border-b-2 border-blue-500 font-bold text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-300' ?>" data-tab="simples">
            Média Simples
        </div>
        <div class="tab px-5 py-2 cursor-pointer <?= $aba_ativa==='ponderada' ? 'border-b-2 border-blue-500 font-bold text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-300' ?>" data-tab="ponderada">
            Média Ponderada
        </div>
    </div>

    <!-- Aba Simples -->
    <div id="simples" class="tab-content <?= $aba_ativa==='simples'?'block':'hidden' ?>">
        <form method="post" class="space-y-4">
            <input type="hidden" name="tipo" value="simples">
            <textarea name="valores"
                class="w-full h-28 p-4 rounded-lg border dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500"
                placeholder="Ex: 7, 8, 10"><?= htmlspecialchars($_POST['valores'] ?? '') ?></textarea>
            <button class="w-full py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 transition font-semibold">
                Calcular Média Simples
            </button>
        </form>
    </div>

    <!-- Aba Ponderada -->
    <div id="ponderada" class="tab-content <?= $aba_ativa==='ponderada'?'block':'hidden' ?>">
        <form method="post" class="space-y-4">
            <input type="hidden" name="tipo" value="ponderada">
            <textarea name="valores_ponderada"
                class="w-full h-28 p-4 rounded-lg border dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500"
                placeholder="Ex: 7:2, 8:3"><?= htmlspecialchars($_POST['valores_ponderada'] ?? '') ?></textarea>
            <button class="w-full py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 transition font-semibold">
                Calcular Média Ponderada
            </button>
        </form>
    </div>

    <!-- Mensagens -->
    <?php if ($error): ?>
        <div class="mt-4 text-red-500 font-semibold"><?= $error ?></div>
    <?php elseif ($resultado): ?>
        <div class="mt-4 p-4 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-100">
            <?= $resultado ?>
        </div>
    <?php endif; ?>

</div>

<script src="assets/js/theme.js"></script>
<script src="assets/js/tabs.js"></script>
</body>
</html>
