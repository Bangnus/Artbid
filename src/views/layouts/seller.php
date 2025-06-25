<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Artbid' ?></title>
    <link href="/artbid/public/output.css" rel="stylesheet">
</head>

<body class="h-screen bg-gray-100 flex">
    <!-- Sidebar -->
    <aside class="min-w-[15%] bg-white shadow-lg p-4">
        <h2 class="text-xl font-bold mb-4">Logo</h2>
        <nav class="space-y-2">
            <a href="#" class="block text-gray-700 hover:text-blue-500">Dashboard</a>
            <a href="#" class="block text-gray-700 hover:text-blue-500">Users</a>
            <a href="#" class="block text-gray-700 hover:text-blue-500">Settings</a>
        </nav>
    </aside>

    <!-- Main -->
    <div class="flex-1 flex flex-col">

        <!-- Navbar -->
        <header class="bg-white h-16 flex items-center justify-between px-6 shadow">
            <h1 class="text-lg font-semibold">Dashboard</h1>
            <div>
                <span class="text-gray-600">Hello, User</span>
            </div>
        </header>

        <!-- Content -->
        <main class="p-6 overflow-y-auto flex-1">
            <div class="bg-white rounded-lg shadow p-4">
                <h2 class="text-xl font-bold mb-2">Main Content</h2>
                <p>This is your dashboard content area.</p>
            </div>
        </main>

    </div>
</body>

</html>