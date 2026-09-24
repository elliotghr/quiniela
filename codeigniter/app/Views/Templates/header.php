<!doctype html>
<html lang="es" data-bs-theme="dark">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= esc($title) ?></title>

    <style>
        [data-theme="dark-icon"],
        [data-theme="light-icon"] {
            display: none;
        }

        [data-bs-theme="dark"] [data-theme="dark-icon"] {
            display: block;
        }

        [data-bs-theme="dark"] [data-theme="light-icon"] {
            display: none;
        }

        [data-bs-theme="light"] [data-theme="light-icon"] {
            display: block;
        }

        [data-bs-theme="light"] [data-theme="dark-icon"] {
            display: none;
        }
    </style>

    <script>
        (function() {
            const savedTheme = localStorage.getItem('darkMode');
            const preferredTheme = savedTheme || (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            document.documentElement.setAttribute('data-bs-theme', preferredTheme);
            document.documentElement.style.colorScheme = preferredTheme;
        })();
    </script>

    <script src="/js/header.js"></script>
    <script src="/js/highcharts.js"></script>
    <script src="/js/general/chart.js"></script>

    <!-- FAVICON -->
    <link rel="apple-touch-icon" sizes="57x57" href="/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
    <link rel="manifest" href="/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

    <?php if (in_array('globalCSS', $HTMLModules)): ?>
        <link rel="stylesheet" href="/css/custom-bootstrap.css">
        <link rel="stylesheet" href="/css/root.css">
    <?php endif; ?>

</head>

<body class="<?= isset($bodyClass) ? $bodyClass : '' ?>">