<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalkulator HPP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom scrollbar untuk mobile */
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 2px;
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-6 max-w-4xl">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800 text-center">
                📊 Kalkulator HPP
            </h1>
            <p class="text-gray-600 text-center mt-2 text-sm md:text-base">
                Hitung Harga Pokok Produksi dengan mudah
            </p>
        </div>

        <form id="hppForm" class="space-y-6">
            <!-- Input Nama Produk -->
            <div class="bg-white rounded-lg shadow-sm p-4 md:p-6">
                <label for="productName" class="block text-sm font-semibold text-gray-700 mb-2">
                    📦 Nama Produk
                </label>
                <input type="text" id="productName" name="productName"
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                    placeholder="Masukkan nama produk..." required>
            </div>

            <!-- Section Bahan-bahan -->
            <div class="bg-white rounded-lg shadow-sm p-4 md:p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">
                        🥘 Bahan-bahan
                    </h3>
                    <button type="button" id="addIngredientButton"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                        <span>+</span>
                        <span class="hidden sm:inline">Tambah Bahan</span>
                    </button>
                </div>

                <div id="ingredientsContainer" class="space-y-4">
                    <div class="ingredient-group border border-gray-200 rounded-lg p-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Bahan</label>
                                <input type="text" name="ingredientName[]"
                                    class="w-full p-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Contoh: Tepung terigu" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Harga per Unit (Rp)</label>
                                <input type="number" name="ingredientPrice[]"
                                    class="w-full p-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="15000" min="0" step="0.01" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah</label>
                                <div class="flex gap-2">
                                    <input type="number" name="ingredientQuantity[]"
                                        class="flex-1 p-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        placeholder="2" min="0" step="0.01" required>
                                    <button type="button"
                                        class="delete-ingredient bg-red-500 hover:bg-red-600 text-white px-3 py-2.5 rounded-lg transition-colors duration-200 hidden"
                                        title="Hapus bahan">
                                        🗑️
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Biaya Tambahan -->
            <div class="bg-white rounded-lg shadow-sm p-4 md:p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    💰 Biaya Tambahan
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="overheadCost" class="block text-sm font-medium text-gray-700 mb-2">
                            Biaya Overhead (Rp)
                        </label>
                        <input type="number" id="overheadCost" name="overheadCost"
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="10000" min="0" step="0.01" required>
                        <p class="text-xs text-gray-500 mt-1">Listrik, gas, tenaga kerja, dll.</p>
                    </div>

                    <div>
                        <label for="targetMargin" class="block text-sm font-medium text-gray-700 mb-2">
                            Target Margin (%)
                        </label>
                        <input type="number" id="targetMargin" name="targetMargin"
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="30" min="0" max="100" step="0.1" required>
                        <p class="text-xs text-gray-500 mt-1">Keuntungan yang diinginkan</p>
                    </div>
                </div>
            </div>

            <!-- Hasil Perhitungan -->
            <div class="bg-white rounded-lg shadow-sm p-4 md:p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    📈 Hasil Perhitungan
                </h3>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <label class="block text-sm font-medium text-blue-700 mb-2">Total Biaya Bahan</label>
                            <div class="flex items-center">
                                <span class="text-sm text-gray-600 mr-2">Rp</span>
                                <input type="text" id="totalMaterialCost"
                                    class="flex-1 bg-transparent text-lg font-semibold text-blue-800 border-0 focus:ring-0 p-0"
                                    placeholder="0" readonly>
                            </div>
                        </div>

                        <div class="bg-green-50 p-4 rounded-lg">
                            <label class="block text-sm font-medium text-green-700 mb-2">Total HPP</label>
                            <div class="flex items-center">
                                <span class="text-sm text-gray-600 mr-2">Rp</span>
                                <input type="text" id="totalHpp"
                                    class="flex-1 bg-transparent text-lg font-semibold text-green-800 border-0 focus:ring-0 p-0"
                                    placeholder="0" readonly>
                            </div>
                        </div>

                        <div class="bg-purple-50 p-4 rounded-lg">
                            <label class="block text-sm font-medium text-purple-700 mb-2">Harga Jual Disarankan</label>
                            <div class="flex items-center">
                                <span class="text-sm text-gray-600 mr-2">Rp</span>
                                <input type="text" id="suggestedPrice"
                                    class="flex-1 bg-transparent text-lg font-semibold text-purple-800 border-0 focus:ring-0 p-0"
                                    placeholder="0" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tombol Hitung -->
            <button type="submit"
                class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white p-4 rounded-lg font-semibold text-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                🧮 Hitung HPP
            </button>
        </form>


    </div>

    <script>
        let ingredientCount = 1;

        // Format number to Rupiah
        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID').format(number);
        }

        // Update delete button visibility
        function updateDeleteButtons() {
            const ingredientGroups = document.querySelectorAll('.ingredient-group');
            const deleteButtons = document.querySelectorAll('.delete-ingredient');

            deleteButtons.forEach(button => {
                if (ingredientGroups.length > 1) {
                    button.classList.remove('hidden');
                } else {
                    button.classList.add('hidden');
                }
            });
        }

        // Add new ingredient
        document.getElementById("addIngredientButton").addEventListener("click", function() {
            ingredientCount++;
            const ingredientGroup = document.createElement("div");
            ingredientGroup.classList.add("ingredient-group", "border", "border-gray-200", "rounded-lg", "p-4");

            ingredientGroup.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Bahan</label>
                        <input 
                            type="text" 
                            name="ingredientName[]" 
                            class="w-full p-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Contoh: Gula pasir"
                            required
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Harga per Unit (Rp)</label>
                        <input 
                            type="number" 
                            name="ingredientPrice[]" 
                            class="w-full p-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="12000"
                            min="0"
                            step="0.01"
                            required
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah</label>
                        <div class="flex gap-2">
                            <input 
                                type="number" 
                                name="ingredientQuantity[]" 
                                class="flex-1 p-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="1"
                                min="0"
                                step="0.01"
                                required
                            >
                            <button 
                                type="button" 
                                class="delete-ingredient bg-red-500 hover:bg-red-600 text-white px-3 py-2.5 rounded-lg transition-colors duration-200"
                                title="Hapus bahan"
                            >
                                🗑️
                            </button>
                        </div>
                    </div>
                </div>
            `;

            document.getElementById("ingredientsContainer").appendChild(ingredientGroup);
            updateDeleteButtons();
        });

        // Delete ingredient with event delegation
        document.getElementById("ingredientsContainer").addEventListener("click", function(e) {
            if (e.target.classList.contains("delete-ingredient")) {
                e.target.closest(".ingredient-group").remove();
                ingredientCount--;
                updateDeleteButtons();
            }
        });

        // Calculate HPP
        document.getElementById("hppForm").addEventListener("submit", function(event) {
            event.preventDefault();

            const ingredientPrices = document.querySelectorAll('input[name="ingredientPrice[]"]');
            const ingredientQuantities = document.querySelectorAll('input[name="ingredientQuantity[]"]');
            const overheadCost = parseFloat(document.getElementById("overheadCost").value) || 0;
            const targetMargin = parseFloat(document.getElementById("targetMargin").value) || 0;

            let totalMaterialCost = 0;

            // Calculate total material cost
            for (let i = 0; i < ingredientPrices.length; i++) {
                const price = parseFloat(ingredientPrices[i].value) || 0;
                const quantity = parseFloat(ingredientQuantities[i].value) || 0;
                totalMaterialCost += price * quantity;
            }

            // Calculate HPP and suggested price
            const totalHpp = totalMaterialCost + overheadCost + (totalMaterialCost * (targetMargin / 100));
            const suggestedPrice = totalHpp + 5000; // Add fixed markup

            // Display results with formatting
            document.getElementById("totalMaterialCost").value = formatRupiah(Math.round(totalMaterialCost));
            document.getElementById("totalHpp").value = formatRupiah(Math.round(totalHpp));
            document.getElementById("suggestedPrice").value = formatRupiah(Math.round(suggestedPrice));

            // Scroll to results (smooth scroll for better UX)
            document.querySelector('[data-results]')?.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        });

        // Initialize delete button visibility
        updateDeleteButtons();

        // Add real-time calculation on input change (optional enhancement)
        document.addEventListener('input', function(e) {
            if (e.target.matches(
                    'input[name="ingredientPrice[]"], input[name="ingredientQuantity[]"], #overheadCost, #targetMargin'
                )) {
                // Auto-calculate if all required fields are filled
                const form = document.getElementById('hppForm');
                const formData = new FormData(form);
                const allFieldsFilled = Array.from(form.querySelectorAll('input[required]')).every(input => input
                    .value.trim() !== '');

                if (allFieldsFilled) {
                    // Trigger calculation after a short delay to avoid too frequent updates
                    clearTimeout(window.calcTimeout);
                    window.calcTimeout = setTimeout(() => {
                        form.dispatchEvent(new Event('submit'));
                    }, 500);
                }
            }
        });
    </script>
</body>

</html>
