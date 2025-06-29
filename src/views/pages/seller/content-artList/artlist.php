<body x-data="{modelOpen: true}">
    <div class="overflow-x-auto ">
        <div class="flex justify-end pr-5 pb-2">
            <button @click="modelOpen = !modelOpen" class="py-1 px-3 bg-bgprimary text-white font-semibold rounded-lg">Create</button>
        </div>
        <table id="artTable" class="table-auto w-full text-sm text-left text-textprimary border-collapse">
            <thead class="bg-gray-200   tracking-wider">
                <tr>
                    <th class="px-4 py-3">#</th>
                    <th class="px-4 py-3">Artworks Name</th>
                    <th class="px-4 py-3">Artist</th>
                    <th class="px-4 py-3">Start Bid</th>
                    <th class="px-4 py-3">Created At</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Action</th>
                </tr>
            </thead>
            <tbody class="[&>tr:nth-child(odd)]:bg-white [&>tr:nth-child(even)]:bg-gray-100">
                <tr class="hover:bg-gray-100 transition">
                    <td class="px-4 py-3">1</td>
                    <td class="px-4 py-3 ">Battaglia di Trafalgar</td>
                    <td class="px-4 py-3">Herman Conrad</td>
                    <td class="px-4 py-3">$620</td>
                    <td class="px-4 py-3">06/29/2025</td>
                    <td class="px-4 py-3">
                        <span class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full font-semibold">
                            Active
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <a href="">
                                <i class="fa-regular fa-eye text-purple-600"></i>
                            </a>
                            <a href="">
                                <i class="fa-regular fa-pen-to-square text-yellow-600"></i>
                            </a>
                            <a href="">
                                <i class="fa-regular fa-trash-can text-red-600"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div x-show="modelOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div @click.away="modelOpen = false" class="bg-white p-6 rounded-xl shadow-lg w-full max-w-lg mx-4">
            <h1 class="text-xl font-bold mb-4">Create New Artwork</h1>
            <form action="" method="post" class="space-y-5">
                <div class="flex items-center justify-center gap-x-5 ">
                    <div class="space-y-2 w-full">
                        <p class="text-[13px] text-textprimary font-primaryRegular">Title</p>
                        <input
                            class="border border-gray-300 p-2 rounded-lg w-full focus:outline-none focus:ring-1 focus:ring-bgprimary transition duration-200"
                            type="text"
                            name="firstname"
                            minlength="4"
                            required>
                    </div>
                    <div class="space-y-2 w-full">
                        <p class="text-[13px] text-textprimary font-primaryRegular">Artist</p>
                        <input
                            class="border border-gray-300 p-2 rounded-lg w-full focus:outline-none focus:ring-1 focus:ring-bgprimary transition duration-200"
                            type="text"
                            name="lastname"
                            minlength="4"
                            required>
                    </div>
                </div>
                <div class="flex items-center justify-center gap-x-5 ">
                    <div class="space-y-2 w-full">
                        <p class="text-[13px] text-textprimary font-primaryRegular">Artwork Name</p>
                        <input
                            class="border border-gray-300 p-2 rounded-lg w-full focus:outline-none focus:ring-1 focus:ring-bgprimary transition duration-200"
                            type="text"
                            name="firstname"
                            minlength="4"
                            required>
                    </div>
                    <div class="space-y-2 w-full">
                        <p class="text-[13px] text-textprimary font-primaryRegular">Year Created</p>
                        <input
                            class="border border-gray-300 p-2 rounded-lg w-full focus:outline-none focus:ring-1 focus:ring-bgprimary transition duration-200"
                            type="text"
                            name="lastname"
                            minlength="4"
                            required>
                    </div>
                </div>
                <div class="flex items-center justify-center gap-x-5 ">
                    <div class="space-y-2 w-full">
                        <p class="text-[13px] text-textprimary font-primaryRegular">Dimensions</p>
                        <input
                            class="border border-gray-300 p-2 rounded-lg w-full focus:outline-none focus:ring-1 focus:ring-bgprimary transition duration-200"
                            type="text"
                            name="firstname"
                            minlength="4"

                            required>
                    </div>
                    <div class="space-y-2 w-full">
                        <p class="text-[13px] text-textprimary font-primaryRegular">Start bid</p>
                        <input
                            class="border border-gray-300 p-2 rounded-lg w-full focus:outline-none focus:ring-1 focus:ring-bgprimary transition duration-200"
                            type="text"
                            name="lastname"
                            minlength="4"

                            required>
                    </div>
                </div>
                <div class="space-y-2 w-full">
                    <p class="text-[13px] text-textprimary font-primaryRegular">Description</p>
                    <input
                        class="border border-gray-300 p-2 rounded-lg w-full focus:outline-none focus:ring-1 focus:ring-bgprimary transition duration-200"
                        type="text"
                        name="lastname"
                        minlength="4"

                        required>
                </div>
                <button id="signin-btn" type="submit" class="bg-textorange text-white w-full text-[14px] font-primaryMedium py-[12px] rounded-lg flex items-center justify-center gap-2">
                    <span id="signin-spinner" class="hidden">
                        <?php require_once __DIR__ . '/../../../../components/content-loading/dot-loading.php'; ?>
                    </span>
                    <span id="signin-text">Create Artwork</span>
                </button>
            </form>
        </div>
    </div>
</body>