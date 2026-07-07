<template>

<div class="container mx-auto p-6">

<div class="dashboard-header">

<h1>
📦 Product Manager Dashboard
</h1>

<p>
Manage products, tags and analytics from one place
</p>

</div>

    <!-- Dashboard -->

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">

        <div class="bg-blue-500 text-white rounded-lg shadow p-5">
            <h3 class="text-lg">Total Products</h3>
            <p class="text-3xl font-bold">
                {{ statistics.total_products }}
            </p>
        </div>

        <div class="bg-green-500 text-white rounded-lg shadow p-5">
            <h3 class="text-lg">Average Price</h3>
            <p class="text-3xl font-bold">
                ${{ statistics.average_price }}
            </p>
        </div>

        <div class="bg-red-500 text-white rounded-lg shadow p-5">
            <h3 class="text-lg">Highest Price</h3>
            <p class="text-3xl font-bold">
                ${{ statistics.highest_price }}
            </p>
        </div>

        <div class="bg-purple-500 text-white rounded-lg shadow p-5">
            <h3 class="text-lg">Total Value</h3>
            <p class="text-3xl font-bold">
                ${{ statistics.total_value }}
            </p>
        </div>

    </div>

    <!-- Add Product -->

    <div class="bg-white rounded-lg shadow-lg p-6 mb-8">

        <h2 class="text-xl font-bold mb-4">
            Add New Product
        </h2>

        <form @submit.prevent="addProduct">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>

                    <label class="block mb-2 font-semibold">
                        Product Name
                    </label>

                    <input
                        v-model="newProduct.name"
                        type="text"
                        class="w-full border rounded p-2"
                        placeholder="Enter product name"
                        required
                    >

                </div>

                <div>

                    <label class="block mb-2 font-semibold">
                        Price
                    </label>

                    <input
                        v-model="newProduct.price"
                        type="number"
                        step="0.01"
                        class="w-full border rounded p-2"
                        placeholder="Enter price"
                        required
                    >

                </div>

            </div>

            <div class="mt-4">

                <label class="block mb-2 font-semibold">
                    Select Tags
                </label>

                <select
                    id="tags-select"
                    multiple
                    class="w-full"
                >

                    <option
                        v-for="tag in tags"
                        :key="tag.id"
                        :value="tag.id"
                    >
                        {{ tag.name }}
                    </option>

                </select>

            </div>

            <button
                class="mt-5 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded"
                type="submit"
            >
                Add Product
            </button>

        </form>

    </div>

    <!-- Search & Sort -->

    <div class="bg-white rounded-lg shadow p-5 mb-5">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <div>

                <input
                    v-model="search"
                    @keyup="loadProducts"
                    type="text"
                    placeholder="Search Product..."
                    class="w-full border rounded p-2"
                >

            </div>

            <div>

                <select
                    v-model="sort"
                    @change="loadProducts"
                    class="w-full border rounded p-2"
                >

                    <option value="">
                        Latest
                    </option>

                    <option value="name_asc">
                        Name A-Z
                    </option>

                    <option value="name_desc">
                        Name Z-A
                    </option>

                    <option value="price_asc">
                        Price Low-High
                    </option>

                    <option value="price_desc">
                        Price High-Low
                    </option>

                </select>

            </div>

        </div>

    </div>                                                                                                                                                                      <!-- Products Table -->

    <div class="bg-white rounded-lg shadow p-6">

        <h2 class="text-xl font-bold mb-4">
            Product List
        </h2>

        <div v-if="products.length == 0" class="text-center text-gray-500 py-8">
            No Products Found
        </div>

        <table v-else class="w-full border-collapse">

            <thead>

                <tr class="bg-gray-100">

                    <th class="border p-3 text-left">#</th>

                    <th class="border p-3 text-left">Name</th>

                    <th class="border p-3 text-left">SKU</th>

                    <th class="border p-3 text-left">Price</th>

                    <th class="border p-3 text-left">Tags</th>

                    <th class="border p-3 text-center">Actions</th>

                </tr>

            </thead>

            <tbody>

                <tr
                    v-for="(product,index) in products"
                    :key="product.id"
                >

                    <td class="border p-3">
                        {{ index + 1 }}
                    </td>

                    <td class="border p-3">
                        {{ product.sku }}
                    </td>

                    <td class="border p-3">
                        {{ product.name }}
                    </td>

                    <td class="border p-3">
                        ${{ product.price }}
                    </td>

                    <td class="border p-3">

                        <span
                            v-for="tag in product.tags"
                            :key="tag.id"
                            class="inline-block bg-blue-100 text-blue-700 px-2 py-1 rounded mr-1 mb-1"
                        >
                            {{ tag.name }}
                        </span>

                    </td>

                    <td class="border p-3 text-center">

                        <button
                            @click="editProduct(product)"
                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded mr-2"
                        >
                            Edit
                        </button>

                        <button
                            @click="deleteProduct(product.id)"
                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded"
                        >
                            Delete
                        </button>

                    </td>

                </tr>

            </tbody>

        </table>

    </div>

    <!-- Edit Modal -->

    <div
        v-if="showEditModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center"
    >

        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6">

            <h2 class="text-2xl font-bold mb-5">
                Edit Product
            </h2>

            <div class="mb-4">

                <label class="block mb-2">
                    Product Name
                </label>

                <input
                    v-model="editForm.name"
                    type="text"
                    class="w-full border rounded p-2"
                >

            </div>

            <div class="mb-4">

                <label class="block mb-2">
                    Price
                </label>

                <input
                    v-model="editForm.price"
                    type="number"
                    class="w-full border rounded p-2"
                >

            </div>

            <div class="mb-4">

                <label class="block mb-2">
                    Tags
                </label>

                <select
                    id="edit-tags-select"
                    multiple
                    class="w-full"
                >

                    <option
                        v-for="tag in tags"
                        :key="tag.id"
                        :value="tag.id"
                    >
                        {{ tag.name }}
                    </option>

                </select>

            </div>

            <div class="flex justify-end gap-2">

                <button
                    @click="showEditModal=false"
                    class="bg-gray-500 text-white px-4 py-2 rounded"
                >
                    Cancel
                </button>

                <button
                    @click="updateProduct"
                    class="bg-green-600 text-white px-4 py-2 rounded"
                >
                    Update
                </button>

            </div>

        </div>

    </div>

    <!-- Tag Analytics Section -->
<div class="bg-white rounded-lg shadow p-6 mt-6">

    <h2 class="text-xl font-bold mb-4">
        Tag Analytics
    </h2>

    <div v-if="tagAnalytics.length === 0" class="text-gray-500">
        No analytics available
    </div>

    <ul v-else>
        <li
            v-for="tag in tagAnalytics"
            :key="tag.id"
            class="flex justify-between border-b py-2"
        >
            <span>{{ tag.name }}</span>
            <span class="font-bold text-blue-600">
                {{ tag.products_count }}
            </span>
        </li>
    </ul>

</div>

</div>
</template> 

<script>
import axios from "axios";

export default {
    name: "App",

    data() {
        return {

            // Products
            products: [],

            // Tags
            tags: [],

            // Dashboard Statistics
            statistics: {
                total_products: 0,
                average_price: 0,
                highest_price: 0,
                lowest_price: 0,
                total_value: 0
            },

            // Tag Analytics
            tagAnalytics: [],

            // Search
            search: "",

            // Sort
            sort: "",

            // Show Edit Modal
            showEditModal: false,

            // Add Product Form
            newProduct: {
                name: "",
                price: "",
                tags: []
            },

            // Edit Product Form
            editForm: {
                id: "",
                name: "",
                price: "",
                tags: []
            }

        };
    },

    mounted() {

        this.loadProducts();

        this.loadTags();

        this.loadStatistics();

        this.loadTagAnalytics(); // ADD THIS

        this.$nextTick(() => {

            this.initSelect2();

        });

    },

    methods: {

        // Load Products

        async loadProducts() {

            try {

                const response = await axios.get("/api/products", {

                    params: {

                        search: this.search,

                        sort: this.sort

                    }

                });

                this.products = response.data;

            } catch (error) {

                console.error(error);

            }

        },

        // Load Tags

        async loadTags() {

            try {

                const response = await axios.get("/api/tags");

                this.tags = response.data;

                this.$nextTick(() => {

                    this.initSelect2();

                });

            } catch (error) {

                console.error(error);

            }

        },

        // Dashboard Statistics

        async loadStatistics() {

            try {

                const response = await axios.get("/api/statistics");

                this.statistics = response.data;

            } catch (error) {

                console.error(error);

            }

        },

        // Initialize Select2

        initSelect2() {

            if ($("#tags-select").hasClass("select2-hidden-accessible")) {

                $("#tags-select").select2("destroy");

            }

            $("#tags-select").select2({

                placeholder: "Select Tags",

                allowClear: true,

                width: "100%"

            });

            $("#tags-select").off("change");

            $("#tags-select").on("change", () => {

                this.newProduct.tags = $("#tags-select").val() || [];

            });

        },        // Add Product

        async addProduct() {

            try {

                const response = await axios.post("/api/products", {

                    name: this.newProduct.name,

                    price: parseFloat(this.newProduct.price),

                    tags: this.newProduct.tags

                });

                this.products.unshift(response.data);

                this.newProduct = {

                    name: "",

                    price: "",

                    tags: []

                };

                $("#tags-select").val(null).trigger("change");

                this.loadStatistics();

                alert("Product added successfully.");

            } catch (error) {

                console.error(error);

                alert("Unable to add product.");

            }

        },

        // Open Edit Modal

        editProduct(product) {

            this.editForm.id = product.id;

            this.editForm.name = product.name;

            this.editForm.price = product.price;

            this.editForm.tags = product.tags.map(tag => tag.id);

            this.showEditModal = true;

            this.$nextTick(() => {

                if ($("#edit-tags-select").hasClass("select2-hidden-accessible")) {

                    $("#edit-tags-select").select2("destroy");

                }

                $("#edit-tags-select").select2({

                    placeholder: "Select Tags",

                    allowClear: true,

                    width: "100%"

                });

                $("#edit-tags-select")
                    .val(this.editForm.tags)
                    .trigger("change");

                $("#edit-tags-select").off("change");

                $("#edit-tags-select").on("change", () => {

                    this.editForm.tags =
                        $("#edit-tags-select").val() || [];

                });

            });

        },

        // Update Product

        async updateProduct() {

            try {

                const response = await axios.put(

                    "/api/products/" + this.editForm.id,

                    {

                        name: this.editForm.name,

                        price: parseFloat(this.editForm.price),

                        tags: this.editForm.tags

                    }

                );

                const index = this.products.findIndex(

                    product => product.id === this.editForm.id

                );

                if (index !== -1) {

                    this.products.splice(

                        index,

                        1,

                        response.data

                    );

                }

                this.showEditModal = false;

                this.loadStatistics();

                alert("Product updated successfully.");

            } catch (error) {

                console.error(error);

                alert("Unable to update product.");

            }

        },

        // Delete Product

        async deleteProduct(id) {

            if (!confirm("Delete this product?")) {

                return;

            }

            try {

                await axios.delete("/api/products/" + id);

                this.products = this.products.filter(

                    product => product.id !== id

                );

                this.loadStatistics();

                alert("Product deleted successfully.");

            } catch (error) {

                console.error(error);

                alert("Unable to delete product.");

            }

        },

            // Tag Analytics
    async loadTagAnalytics() {
        try {
            const response = await axios.get("/api/tags/analytics");
            this.tagAnalytics = response.data;
        } catch (error) {
            console.error(error);
        }
    }

    }

};
</script>

<style>

body{
    background:#f3f4f6;
    font-family:Arial, Helvetica, sans-serif;
}

table{
    width:100%;
}

th{
    background:#f8fafc;
    font-weight:bold;
}

th,td{
    border:1px solid #ddd;
    padding:12px;
}

button{
    transition:.3s;
    cursor:pointer;
}

button:hover{
    opacity:.9;
}

.select2-container{
    width:100%!important;
}

.select2-selection{
    min-height:40px!important;
}

</style>
