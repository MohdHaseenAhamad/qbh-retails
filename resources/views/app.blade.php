<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <title>Qbh-Ebill - Self Hosted Invoicing Platform</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="apple-touch-icon" sizes="180x180" href="/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicons/favicon-16x16.png">
    <link rel="manifest" href="/favicons/site.webmanifest">
    <link rel="mask-icon" href="/favicons/safari-pinned-tab.svg" color="#5851d8">
    <link rel="shortcut icon" href="/favicons/favicon.ico">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-config" content="/favicons/browserconfig.xml">
    <meta name="theme-color" content="#ffffff">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite
        <!-- <script type="module" src="http://localhost:3000/resources/scripts/main.js"></script> -->
        <!-- <link rel="stylesheet" href="http://127.0.0.1:8000/build/assets/main.0f3882e4.css" /> -->

</head>

<body class="h-full overflow-hidden bg-gray-100 font-base">
    <script type="module">
        window.Crater.start()
    </script>
</body>

</html>
