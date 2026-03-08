<?php
header('Content-Type: text/html; charset=UTF-8');

if (!defined('HEADER_INCLUDED')) {
    define('HEADER_INCLUDED', true);
    ?>
    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Laserdreams | Catálogo Premium</title>

        <!-- Tailwind CSS -->
        <script src="https://cdn.tailwindcss.com"></script>

        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
            rel="stylesheet">

        <style>
            body {
                font-family: 'Plus Jakarta Sans', sans-serif;
                background-color: #fbfbfb;
                color: #1a1a1a;
            }

            .bg-premium-dark {
                background-color: #121212;
            }
        </style>

        <!-- JS CORE DEL CARRITO (DEBE ESTAR ANTES DE onclick) -->
        <script src="/assets/js/carrito.js"></script>
    </head>

    <body class="antialiased">

        <header class="bg-[#121212] text-white sticky top-0 z-50 shadow-2xl">
            <div class="max-w-7xl mx-auto px-4 md:px-8 py-4 flex justify-between items-center">
                <a href="/" class="flex items-center gap-3 group">
                    <span class="text-2xl font-black tracking-tighter transition-all group-hover:text-violet-500">
                        LASER<span class="text-violet-500 group-hover:text-white">DREAMS</span>
                    </span>
                </a>

                <nav class="hidden md:flex items-center space-x-10 text-xs font-black uppercase tracking-[0.2em]">
                    <a href="/catalogo.php"
                        class="hover:text-violet-500 transition-colors py-2 border-b-2 border-transparent hover:border-violet-500">Catálogo</a>
                </nav>

                <div class="flex items-center gap-6">
                    <a href="/carrito.php" id="cartButton"
                        class="bg-violet-500 text-black px-6 py-2.5 rounded-full flex items-center gap-3 transition-all hover:bg-white hover:scale-105 shadow-lg shadow-violet-500/20">
                        <span class="text-lg">🛒</span>
                        <span class="font-black text-xs uppercase tracking-wider">Carrito</span>
                        <span id="carrito-contador"
                            class="bg-black text-white text-[10px] font-black rounded-full w-5 h-5 flex items-center justify-center">0</span>
                    </a>
                </div>
            </div>
        </header>

        <main class="min-h-screen">
            <?php
}
?>