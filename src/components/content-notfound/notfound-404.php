<head>
    <link href="/output.css" rel="stylesheet">
</head>

<body class="bg-white">
    <div class="flex items-center justify-center h-screen">
        <div class="mx-auto max-w-screen-sm text-center">
            <h1 class="mb-4 text-7xl tracking-tight font-extrabold lg:text-9xl font-primaryBold text-textorange">404
            </h1>
            <p class="mb-4 text-3xl tracking-tight font-primaryBold text-textprimary md:text-4xl ">Something's missing.
            </p>
            <p class="mb-4 text-lg font-primaryRegular text-textsecondary">Sorry, we can't find that page. You'll find
                lots to explore on the home page. </p>
            <a href="<?= $_ENV['APP_URL'] ?>/"
                class="inline-flex text-white bg-bgprimary  focus:ring-4 focus:outline-none focus:ring-bgprimary font-primaryMedium rounded-lg text-sm px-5 py-2.5 text-center my-4">Back
                to Homepage</a>
        </div>
    </div>
</body>