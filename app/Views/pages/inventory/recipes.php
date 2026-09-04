<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900"><?= htmlspecialchars($title) ?></h1>
            <p class="text-sm text-gray-500 mt-1">Pilih produk atau varian untuk mengatur komposisi resep (Bill of Materials).</p>
        </div>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <ul role="list" class="divide-y divide-gray-200">
            <?php if(empty($products)): ?>
                <li class="px-4 py-8 text-center text-gray-500">Belum ada produk yang tersedia.</li>
            <?php else: ?>
                <?php foreach($products as $product): ?>
                    <li>
                        <div class="px-4 py-4 sm:px-6 hover:bg-gray-50">
                            <div class="flex items-center justify-between">
                                <div class="flex flex-col">
                                    <p class="text-sm font-medium text-indigo-600 truncate">
                                        <?= htmlspecialchars($product['name']) ?>
                                    </p>
                                    <?php if(!$product['has_variants']): ?>
                                    <p class="mt-1 text-xs text-gray-500">Resep dasar (Tanpa Varian)</p>
                                    <?php endif; ?>
                                </div>
                                <div class="flex-shrink-0 flex gap-2">
                                    <?php if(!$product['has_variants']): ?>
                                        <a href="<?= BASE_URL ?>/inventory/recipes/<?= $product['id'] ?>" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            Kelola Resep
                                        </a>
                                    <?php else: ?>
                                        <div x-data="{ open: false }" class="relative inline-block text-left">
                                            <div>
                                                <button @click="open = !open" @click.away="open = false" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                    Pilih Varian
                                                    <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </div>
                                            <div x-show="open" style="display: none;" x-transition class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10 focus:outline-none">
                                                <div class="py-1" role="menu" aria-orientation="vertical">
                                                    <?php foreach($product['variants'] as $variant): ?>
                                                        <a href="<?= BASE_URL ?>/inventory/recipes/<?= $product['id'] ?>/<?= $variant['id'] ?>" class="text-gray-700 block px-4 py-2 text-sm hover:bg-indigo-50 hover:text-indigo-700" role="menuitem">
                                                            Varian: <?= htmlspecialchars($variant['name']) ?>
                                                        </a>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>
</div>

