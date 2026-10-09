<template>
<div class="min-h-screen bg-slate-100 text-slate-800 p-4 md:p-8">
  <!-- Top Navigation Header -->
  <div class="max-w-7xl mx-auto mb-8 bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
    <div>
      <div class="flex items-center gap-3">
        <div class="p-3 bg-gradient-to-tr from-blue-600 to-indigo-600 rounded-xl text-white shadow-md">
          <i class="fa-solid fa-tags text-2xl"></i>
        </div>
        <div>
          <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Smart Tag Studio & Product Manager</h1>
          <p class="text-sm text-slate-500 font-medium">Laravel 12 + Vue 3 + Select2 Ajax Engine & Real-Time Analytics</p>
        </div>
      </div>
    </div>
    
    <!-- Tab Switcher -->
    <div class="flex bg-slate-100 p-1.5 rounded-xl border border-slate-200">
      <button 
        @click="switchTab('products')"
        :class="['px-4 py-2 text-sm font-semibold rounded-lg transition-all flex items-center gap-2', activeTab === 'products' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-600 hover:text-slate-900']"
      >
        <i class="fa-solid fa-box"></i> Products & Tagging
      </button>
      <button 
        @click="switchTab('analytics')"
        :class="['px-4 py-2 text-sm font-semibold rounded-lg transition-all flex items-center gap-2', activeTab === 'analytics' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-600 hover:text-slate-900']"
      >
        <i class="fa-solid fa-chart-pie"></i> Analytics & Tag Tools
      </button>
    </div>
  </div>

  <!-- Main Container -->
  <div class="max-w-7xl mx-auto space-y-8">
    
    <!-- DASHBOARD STATS CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
      <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex items-center gap-4">
        <div class="p-3 bg-blue-100 text-blue-600 rounded-xl">
          <i class="fa-solid fa-boxes-stacked text-xl"></i>
        </div>
        <div>
          <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Products</span>
          <h3 class="text-2xl font-bold text-slate-800">{{ statistics.total_products || 0 }}</h3>
        </div>
      </div>

      <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex items-center gap-4">
        <div class="p-3 bg-emerald-100 text-emerald-600 rounded-xl">
          <i class="fa-solid fa-dollar-sign text-xl"></i>
        </div>
        <div>
          <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Average Price</span>
          <h3 class="text-2xl font-bold text-slate-800">${{ statistics.average_price || '0.00' }}</h3>
        </div>
      </div>

      <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex items-center gap-4">
        <div class="p-3 bg-purple-100 text-purple-600 rounded-xl">
          <i class="fa-solid fa-hashtag text-xl"></i>
        </div>
        <div>
          <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Tags</span>
          <h3 class="text-2xl font-bold text-slate-800">{{ statistics.total_tags || 0 }}</h3>
        </div>
      </div>

      <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex items-center gap-4">
        <div class="p-3 bg-amber-100 text-amber-600 rounded-xl">
          <i class="fa-solid fa-vault text-xl"></i>
        </div>
        <div>
          <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Value</span>
          <h3 class="text-2xl font-bold text-slate-800">${{ statistics.total_value || '0.00' }}</h3>
        </div>
      </div>

      <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex items-center gap-4">
        <div class="p-3 bg-rose-100 text-rose-600 rounded-xl">
          <i class="fa-solid fa-broom text-xl"></i>
        </div>
        <div>
          <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Unused Tags</span>
          <h3 class="text-2xl font-bold text-slate-800">{{ tagSummary.orphans || 0 }}</h3>
        </div>
      </div>
    </div>

    <!-- TAB 1: PRODUCTS & TAGGING STUDIO -->
    <div v-show="activeTab === 'products'" class="space-y-8">
      
      <!-- ADD NEW PRODUCT CARD -->
      <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
          <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
            <i class="fa-solid fa-plus-circle text-blue-600"></i> Add New Product with Select2 Ajax Tagging
          </h2>
          <span class="text-xs font-semibold bg-blue-50 text-blue-700 px-3 py-1 rounded-full border border-blue-200">
            <i class="fa-solid fa-wand-magic-sparkles"></i> Type & press enter to auto-create new tags!
          </span>
        </div>

        <form @submit.prevent="addProduct" class="space-y-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-semibold text-slate-700 mb-2">Product Name</label>
              <input 
                v-model="newProduct.name" 
                type="text" 
                placeholder="e.g. Wireless Noise-Canceling Headphones"
                class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                required
              >
            </div>

            <div>
              <label class="block text-sm font-semibold text-slate-700 mb-2">Price ($)</label>
              <input 
                v-model="newProduct.price" 
                type="number" 
                step="0.01" 
                placeholder="99.99"
                class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                required
              >
            </div>
          </div>

          <div>
            <div class="flex justify-between items-center mb-2">
              <label class="block text-sm font-semibold text-slate-700">Select or Create Tags (Select2 Ajax Multi-Select)</label>
              <span class="text-xs text-slate-400">Supports infinite scrolling & on-the-fly tag creation</span>
            </div>
            <!-- Clean Empty Select element managed exclusively by Select2 -->
            <select id="tags-select" multiple class="w-full"></select>
          </div>

          <div class="flex justify-end">
            <button 
              type="submit" 
              class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow-md hover:shadow-lg transition-all flex items-center gap-2"
            >
              <i class="fa-solid fa-plus"></i> Save Product
            </button>
          </div>
        </form>
      </div>

      <!-- FILTER, SEARCH & EXPORT TOOLBAR -->
      <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5">
        <div class="flex flex-col lg:flex-row justify-between items-stretch lg:items-center gap-4">
          
          <!-- Search & Filter Controls -->
          <div class="flex flex-wrap items-center gap-3 flex-1">
            <div class="relative flex-1 min-w-[200px]">
              <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3 text-slate-400"></i>
              <input 
                v-model="search" 
                @input="loadProducts" 
                type="text" 
                placeholder="Search by Product Name or SKU..."
                class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition-all"
              >
            </div>

            <!-- Filter by Tag -->
            <select 
              v-model="selectedTagFilter" 
              @change="loadProducts"
              class="px-4 py-2.5 rounded-xl border border-slate-300 text-sm font-medium focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white"
            >
              <option value="">All Tags Filter</option>
              <option v-for="tag in allTagsList" :key="tag.id" :value="tag.id">
                🏷️ {{ tag.name }} ({{ tag.products_count || 0 }})
              </option>
            </select>

            <!-- Sort dropdown -->
            <select 
              v-model="sort" 
              @change="loadProducts"
              class="px-4 py-2.5 rounded-xl border border-slate-300 text-sm font-medium focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white"
            >
              <option value="">Sort: Newest First</option>
              <option value="name_asc">Name: A to Z</option>
              <option value="name_desc">Name: Z to A</option>
              <option value="price_asc">Price: Low to High</option>
              <option value="price_desc">Price: High to Low</option>
            </select>
          </div>

          <!-- Multi-Format Export Studio -->
          <div class="flex items-center gap-2">
            <button 
              @click="exportCSV" 
              class="px-3.5 py-2.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5"
            >
              <i class="fa-solid fa-file-csv text-base"></i> CSV
            </button>

            <button 
              @click="exportExcel" 
              class="px-3.5 py-2.5 bg-teal-50 hover:bg-teal-100 text-teal-700 border border-teal-200 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5"
            >
              <i class="fa-solid fa-file-excel text-base"></i> Excel
            </button>

            <button 
              @click="exportPrintPDF" 
              class="px-3.5 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5"
            >
              <i class="fa-solid fa-file-pdf text-base"></i> PDF / Print
            </button>
          </div>

        </div>
      </div>

      <!-- BULK SELECTION ACTION BAR -->
      <div v-if="selectedProductIds.length > 0" class="bg-indigo-900 text-white rounded-2xl shadow-md p-4 flex flex-col sm:flex-row justify-between items-center gap-4 transition-all">
        <div class="flex items-center gap-3">
          <span class="bg-indigo-700 text-white text-xs font-bold px-3 py-1 rounded-full">
            {{ selectedProductIds.length }} Selected
          </span>
          <span class="text-sm font-medium">Bulk Actions:</span>
        </div>

        <div class="flex flex-wrap items-center gap-3">
          <!-- Select Tags for Bulk Action -->
          <div class="w-64">
            <select id="bulk-tags-select" multiple class="w-full text-slate-800"></select>
          </div>

          <button 
            @click="executeBulkTagAction('attach')"
            class="px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg text-xs font-bold transition-all"
          >
            <i class="fa-solid fa-plus"></i> Attach Tags
          </button>

          <button 
            @click="executeBulkTagAction('detach')"
            class="px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white rounded-lg text-xs font-bold transition-all"
          >
            <i class="fa-solid fa-minus"></i> Detach Tags
          </button>

          <button 
            @click="executeBulkTagAction('sync')"
            class="px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white rounded-lg text-xs font-bold transition-all"
          >
            <i class="fa-solid fa-rotate"></i> Sync Tags
          </button>

          <button 
            @click="executeBulkDelete"
            class="px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-xs font-bold transition-all"
          >
            <i class="fa-solid fa-trash"></i> Delete Selected
          </button>
        </div>
      </div>

      <!-- PRODUCTS TABLE -->
      <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex justify-between items-center">
          <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
            <i class="fa-solid fa-list-check text-slate-600"></i> Product Catalog
          </h2>
          <span class="text-xs text-slate-500 font-medium">Showing {{ products.length }} item(s)</span>
        </div>

        <div v-if="products.length === 0" class="text-center py-12 text-slate-400">
          <i class="fa-solid fa-folder-open text-4xl mb-3 text-slate-300"></i>
          <p class="text-base font-semibold">No Products Found</p>
          <p class="text-xs text-slate-400 mt-1">Try changing search filters or create a new product above.</p>
        </div>

        <div v-else class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="bg-slate-50 text-slate-500 text-xs uppercase font-bold tracking-wider border-b border-slate-200">
                <th class="p-4 w-10 text-center">
                  <input 
                    type="checkbox" 
                    :checked="isAllSelected" 
                    @change="toggleSelectAll"
                    class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer"
                  >
                </th>
                <th class="p-4">SKU</th>
                <th class="p-4">Product Name</th>
                <th class="p-4">Price</th>
                <th class="p-4">Color-Coded Tags</th>
                <th class="p-4 text-right">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
              <tr v-for="product in products" :key="product.id" class="hover:bg-slate-50/80 transition-all">
                <td class="p-4 text-center">
                  <input 
                    type="checkbox" 
                    :value="product.id" 
                    v-model="selectedProductIds"
                    class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer"
                  >
                </td>
                <td class="p-4 font-mono text-xs font-bold text-slate-500">
                  {{ product.sku }}
                </td>
                <td class="p-4 font-semibold text-slate-900">
                  {{ product.name }}
                </td>
                <td class="p-4 font-semibold text-emerald-600">
                  ${{ parseFloat(product.price).toFixed(2) }}
                </td>
                <td class="p-4">
                  <div class="flex flex-wrap gap-1.5">
                    <span 
                      v-for="tag in product.tags" 
                      :key="tag.id"
                      class="px-2.5 py-1 rounded-full text-xs font-semibold text-white shadow-sm flex items-center gap-1"
                      :style="{ backgroundColor: tag.color || '#3B82F6' }"
                    >
                      <i class="fa-solid fa-tag text-[10px]"></i> {{ tag.name }}
                    </span>
                    <span v-if="!product.tags || product.tags.length === 0" class="text-xs text-slate-400 italic">
                      No tags attached
                    </span>
                  </div>
                </td>
                <td class="p-4 text-right">
                  <div class="flex justify-end gap-2">
                    <button 
                      @click="editProduct(product)" 
                      class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-all"
                      title="Edit Product"
                    >
                      <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                    <button 
                      @click="deleteProduct(product.id)" 
                      class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition-all"
                      title="Delete Product"
                    >
                      <i class="fa-solid fa-trash-can"></i>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

    </div>

    <!-- TAB 2: REAL-TIME TAG ANALYTICS & TOOLS -->
    <div v-show="activeTab === 'analytics'" class="space-y-8">
      
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- CHART.JS TAG POPULARITY GRAPH -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
          <div class="flex justify-between items-center mb-6">
            <div>
              <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-chart-column text-blue-600"></i> Tag Popularity & Distribution Chart
              </h2>
              <p class="text-xs text-slate-400 mt-1">Real-time counts of products associated per tag</p>
            </div>
            <button 
              @click="loadTagAnalytics" 
              class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold transition-all flex items-center gap-1"
            >
              <i class="fa-solid fa-arrows-rotate"></i> Refresh
            </button>
          </div>

          <div class="h-80 relative">
            <canvas id="tagChart"></canvas>
          </div>
        </div>

        <!-- ORPHAN TAG WATCHDOG & CLEANUP -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex flex-col justify-between">
          <div>
            <div class="flex items-center justify-between mb-4">
              <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-shield-cat text-rose-600"></i> Orphan Tag Watchdog
              </h2>
              <span class="px-2.5 py-1 bg-rose-100 text-rose-700 rounded-full text-xs font-bold">
                {{ orphanTagsList.length }} Unused
              </span>
            </div>

            <p class="text-xs text-slate-500 mb-4">
              Orphan tags are tags not linked to any active product. Clean them up to maintain database health.
            </p>

            <div class="bg-slate-50 rounded-xl p-3 max-h-48 overflow-y-auto space-y-2 border border-slate-200">
              <div 
                v-for="tag in orphanTagsList" 
                :key="tag.id"
                class="flex justify-between items-center bg-white px-3 py-2 rounded-lg border border-slate-200 text-xs"
              >
                <span class="font-semibold text-slate-700 flex items-center gap-1.5">
                  <span class="w-2.5 h-2.5 rounded-full" :style="{ backgroundColor: tag.color }"></span>
                  {{ tag.name }}
                </span>
                <span class="text-[10px] text-slate-400">0 Products</span>
              </div>

              <div v-if="orphanTagsList.length === 0" class="text-center py-6 text-slate-400 text-xs font-medium">
                🎉 No orphan tags found! Clean database!
              </div>
            </div>
          </div>

          <button 
            @click="cleanupOrphans" 
            :disabled="orphanTagsList.length === 0"
            class="mt-6 w-full py-3 bg-rose-600 hover:bg-rose-700 disabled:opacity-50 text-white font-bold rounded-xl shadow transition-all flex items-center justify-center gap-2"
          >
            <i class="fa-solid fa-broom"></i> Clean Up All {{ orphanTagsList.length }} Unused Tags
          </button>
        </div>

      </div>

      <!-- SMART TAG MERGING ENGINE CARD -->
      <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <div class="mb-6">
          <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
            <i class="fa-solid fa-code-merge text-indigo-600"></i> Smart Tag Merging Studio
          </h2>
          <p class="text-xs text-slate-500 mt-1">
            Seamlessly combine duplicate or similar tags (e.g. merge "VueJS" into "Vue.js"). All existing product links are automatically reassigned to the target tag.
          </p>
        </div>

        <form @submit.prevent="mergeTags" class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">1. Source Tag (To be merged & deleted)</label>
            <select 
              v-model="mergeSourceId" 
              class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm font-medium focus:ring-2 focus:ring-indigo-500"
              required
            >
              <option value="">Select Source Tag</option>
              <option v-for="tag in allTagsList" :key="tag.id" :value="tag.id">
                {{ tag.name }} ({{ tag.products_count || 0 }} products)
              </option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">2. Target Tag (To retain product links)</label>
            <select 
              v-model="mergeTargetId" 
              class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm font-medium focus:ring-2 focus:ring-indigo-500"
              required
            >
              <option value="">Select Target Tag</option>
              <option 
                v-for="tag in allTagsList" 
                :key="tag.id" 
                :value="tag.id"
                :disabled="tag.id === mergeSourceId"
              >
                {{ tag.name }} ({{ tag.products_count || 0 }} products)
              </option>
            </select>
          </div>

          <div>
            <button 
              type="submit" 
              class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow transition-all flex items-center justify-center gap-2"
            >
              <i class="fa-solid fa-code-merge"></i> Merge Tags Now
            </button>
          </div>
        </form>
      </div>

    </div>

    <!-- EDIT PRODUCT MODAL -->
    <div v-if="showEditModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm flex justify-center items-center z-50 p-4">
      <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 border border-slate-200 animate-in fade-in zoom-in duration-200">
        <div class="flex justify-between items-center mb-5 pb-3 border-b border-slate-100">
          <h2 class="text-xl font-bold text-slate-900 flex items-center gap-2">
            <i class="fa-solid fa-pen-to-square text-amber-500"></i> Edit Product
          </h2>
          <button @click="showEditModal = false" class="text-slate-400 hover:text-slate-600">
            <i class="fa-solid fa-xmark text-lg"></i>
          </button>
        </div>

        <form @submit.prevent="updateProduct" class="space-y-4">
          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1">Product Name</label>
            <input 
              v-model="editForm.name" 
              type="text" 
              class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-amber-500"
              required
            >
          </div>

          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1">Price ($)</label>
            <input 
              v-model="editForm.price" 
              type="number" 
              step="0.01"
              class="w-full px-4 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-amber-500"
              required
            >
          </div>

          <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1">Associated Tags (Select2 Ajax)</label>
            <select id="edit-tags-select" multiple class="w-full"></select>
          </div>

          <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
            <button 
              type="button" 
              @click="showEditModal = false"
              class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold rounded-xl text-sm transition-all"
            >
              Cancel
            </button>
            <button 
              type="submit" 
              class="px-5 py-2 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-xl text-sm shadow transition-all flex items-center gap-1.5"
            >
              <i class="fa-solid fa-check"></i> Update Product
            </button>
          </div>
        </form>
      </div>
    </div>

  </div>
</div>
</template>

<script>
import axios from "axios";

export default {
  name: "App",
  data() {
    return {
      activeTab: "products",
      products: [],
      allTagsList: [],
      orphanTagsList: [],
      selectedProductIds: [],
      statistics: {
        total_products: 0,
        total_tags: 0,
        average_price: "0.00",
        highest_price: "0.00",
        lowest_price: "0.00",
        total_value: "0.00"
      },
      tagSummary: {
        total: 0,
        used: 0,
        orphans: 0
      },
      search: "",
      sort: "",
      selectedTagFilter: "",
      showEditModal: false,
      newProduct: {
        name: "",
        price: "",
        tags: []
      },
      editForm: {
        id: null,
        name: "",
        price: "",
        tags: []
      },
      mergeSourceId: "",
      mergeTargetId: "",
      chartInstance: null
    };
  },

  computed: {
    isAllSelected() {
      return this.products.length > 0 && this.selectedProductIds.length === this.products.length;
    }
  },

  mounted() {
    this.loadProducts();
    this.loadStatistics();
    this.loadTagAnalytics();
    this.loadOrphanTags();
    
    this.ensureSelect2Init();
  },

  methods: {
    switchTab(tab) {
      this.activeTab = tab;
      if (tab === 'products') {
        this.ensureSelect2Init();
      } else if (tab === 'analytics') {
        this.$nextTick(() => {
          this.renderTagChart(this.allTagsList);
        });
      }
    },

    ensureSelect2Init() {
      this.$nextTick(() => {
        setTimeout(() => {
          this.initSelect2AddProduct();
          if (this.selectedProductIds.length > 0) {
            this.initSelect2BulkAction();
          }
        }, 150);
      });
    },

    showToast(icon, title) {
      if (window.Swal) {
        window.Swal.mixin({
          toast: true,
          position: 'top-end',
          showConfirmButton: false,
          timer: 3000,
          timerProgressBar: true
        }).fire({ icon, title });
      }
    },

    async loadProducts() {
      try {
        const response = await axios.get("/api/products", {
          params: {
            search: this.search,
            sort: this.sort,
            tag_id: this.selectedTagFilter
          }
        });
        this.products = response.data;
      } catch (error) {
        console.error("Error loading products:", error);
      }
    },

    async loadStatistics() {
      try {
        const response = await axios.get("/api/statistics");
        this.statistics = response.data;
      } catch (error) {
        console.error("Error loading statistics:", error);
      }
    },

    async loadTagAnalytics() {
      try {
        const response = await axios.get("/api/tags/analytics");
        this.allTagsList = response.data.tags || [];
        this.tagSummary = response.data.summary || {};
        
        // Populate options into select2 elements after tags arrive
        this.initSelect2AddProduct();

        if (this.activeTab === 'analytics') {
          this.$nextTick(() => {
            this.renderTagChart(this.allTagsList);
          });
        }
      } catch (error) {
        console.error("Error loading tag analytics:", error);
      }
    },

    async loadOrphanTags() {
      try {
        const response = await axios.get("/api/tags/orphans");
        this.orphanTagsList = response.data;
      } catch (error) {
        console.error("Error loading orphan tags:", error);
      }
    },

    initSelect2AddProduct() {
      const self = this;
      const $el = $("#tags-select");

      if (!$el.length || typeof $.fn.select2 === 'undefined') {
        setTimeout(() => self.initSelect2AddProduct(), 200);
        return;
      }

      if ($el.hasClass("select2-hidden-accessible")) {
        $el.select2("destroy");
      }

      // Populate tags as native options inside the empty select tag
      $el.empty();
      if (this.allTagsList && this.allTagsList.length) {
        this.allTagsList.forEach(t => {
          const opt = new Option(t.name, t.id, false, false);
          $el.append(opt);
        });
      }

      $el.select2({
        placeholder: "Search or select tags...",
        allowClear: true,
        width: "100%",
        tags: true,
        ajax: {
          url: "/api/tags",
          dataType: "json",
          delay: 250,
          data: function(params) {
            return {
              search: params.term,
              page: params.page || 1
            };
          },
          processResults: function(data) {
            return {
              results: data.results.map(tag => ({
                id: tag.id,
                text: tag.name,
                color: tag.color
              })),
              pagination: data.pagination
            };
          },
          cache: true
        },
        createTag: function(params) {
          const term = $.trim(params.term);
          if (term === "") return null;
          return {
            id: "new:" + term,
            text: term + " (Create New Tag)",
            isNew: true
          };
        }
      });

      $el.off("select2:select select2:unselect change");

      $el.on("select2:select", async function(e) {
        const data = e.params.data;
        if (data.isNew) {
          const tagName = data.id.replace("new:", "");
          try {
            const res = await axios.post("/api/tags", { name: tagName });
            const createdTag = res.data;

            const newOption = new Option(createdTag.name, createdTag.id, true, true);
            $el.find(`option[value="${data.id}"]`).remove();
            $el.append(newOption).trigger("change");

            self.showToast("success", `New Tag '${createdTag.name}' Created!`);
            self.loadTagAnalytics();
          } catch (err) {
            self.showToast("error", "Failed to create new tag");
            $el.find(`option[value="${data.id}"]`).remove();
          }
        }
        self.newProduct.tags = $el.val() || [];
      });

      $el.on("select2:unselect change", function() {
        self.newProduct.tags = $el.val() || [];
      });
    },

    initSelect2BulkAction() {
      const $el = $("#bulk-tags-select");
      if (!$el.length || typeof $.fn.select2 === 'undefined') return;

      if ($el.hasClass("select2-hidden-accessible")) {
        $el.select2("destroy");
      }

      $el.empty();
      if (this.allTagsList && this.allTagsList.length) {
        this.allTagsList.forEach(t => {
          const opt = new Option(t.name, t.id, false, false);
          $el.append(opt);
        });
      }

      $el.select2({
        placeholder: "Select tags for bulk action...",
        allowClear: true,
        width: "100%",
        ajax: {
          url: "/api/tags",
          dataType: "json",
          delay: 250,
          data: function(params) {
            return { search: params.term, page: params.page || 1 };
          },
          processResults: function(data) {
            return {
              results: data.results.map(tag => ({ id: tag.id, text: tag.name })),
              pagination: data.pagination
            };
          }
        }
      });
    },

    async addProduct() {
      try {
        const tagVals = $("#tags-select").val() || [];
        const cleanTags = tagVals.filter(id => !id.toString().startsWith("new:"));

        const response = await axios.post("/api/products", {
          name: this.newProduct.name,
          price: parseFloat(this.newProduct.price),
          tags: cleanTags
        });

        this.products.unshift(response.data);
        this.newProduct = { name: "", price: "", tags: [] };
        $("#tags-select").val(null).trigger("change");

        this.loadStatistics();
        this.loadTagAnalytics();
        this.showToast("success", "Product added successfully!");
      } catch (error) {
        console.error("Error adding product:", error);
        this.showToast("error", "Unable to add product.");
      }
    },

    editProduct(product) {
      this.editForm.id = product.id;
      this.editForm.name = product.name;
      this.editForm.price = product.price;
      this.editForm.tags = product.tags ? product.tags.map(t => t.id) : [];

      this.showEditModal = true;

      this.$nextTick(() => {
        const $el = $("#edit-tags-select");
        if (!$el.length || typeof $.fn.select2 === 'undefined') return;

        if ($el.hasClass("select2-hidden-accessible")) {
          $el.select2("destroy");
        }

        $el.empty();
        if (product.tags) {
          product.tags.forEach(t => {
            const opt = new Option(t.name, t.id, true, true);
            $el.append(opt);
          });
        }

        $el.select2({
          placeholder: "Search tags...",
          allowClear: true,
          width: "100%",
          ajax: {
            url: "/api/tags",
            dataType: "json",
            delay: 250,
            data: params => ({ search: params.term, page: params.page || 1 }),
            processResults: data => ({
              results: data.results.map(tag => ({ id: tag.id, text: tag.name })),
              pagination: data.pagination
            })
          }
        });

        $el.off("change").on("change", () => {
          this.editForm.tags = $el.val() || [];
        });
      });
    },

    async updateProduct() {
      try {
        const response = await axios.put("/api/products/" + this.editForm.id, {
          name: this.editForm.name,
          price: parseFloat(this.editForm.price),
          tags: this.editForm.tags
        });

        const idx = this.products.findIndex(p => p.id === this.editForm.id);
        if (idx !== -1) {
          this.products.splice(idx, 1, response.data);
        }

        this.showEditModal = false;
        this.loadStatistics();
        this.loadTagAnalytics();
        this.showToast("success", "Product updated successfully!");
      } catch (error) {
        console.error("Error updating product:", error);
        this.showToast("error", "Unable to update product.");
      }
    },

    async deleteProduct(id) {
      const confirm = await window.Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this product deletion!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#ef4444",
        cancelButtonColor: "#64748b",
        confirmButtonText: "Yes, Delete Product"
      });

      if (!confirm.isConfirmed) return;

      try {
        await axios.delete("/api/products/" + id);
        this.products = this.products.filter(p => p.id !== id);
        this.selectedProductIds = this.selectedProductIds.filter(pid => pid !== id);

        this.loadStatistics();
        this.loadTagAnalytics();
        this.loadOrphanTags();
        this.showToast("success", "Product deleted successfully.");
      } catch (error) {
        this.showToast("error", "Failed to delete product.");
      }
    },

    toggleSelectAll() {
      if (this.isAllSelected) {
        this.selectedProductIds = [];
      } else {
        this.selectedProductIds = this.products.map(p => p.id);
      }
      this.ensureSelect2Init();
    },

    async executeBulkTagAction(action) {
      const tagIds = $("#bulk-tags-select").val() || [];
      if (tagIds.length === 0) {
        this.showToast("warning", "Please select at least one tag for bulk action.");
        return;
      }

      try {
        await axios.post("/api/products/bulk-tag", {
          product_ids: this.selectedProductIds,
          tag_ids: tagIds,
          action: action
        });

        this.loadProducts();
        this.loadTagAnalytics();
        this.showToast("success", `Bulk ${action} tags completed!`);
      } catch (error) {
        this.showToast("error", "Bulk tag operation failed.");
      }
    },

    async executeBulkDelete() {
      const confirm = await window.Swal.fire({
        title: `Delete ${this.selectedProductIds.length} Products?`,
        text: "Selected products will be permanently removed.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#ef4444",
        cancelButtonColor: "#64748b",
        confirmButtonText: "Yes, Delete Selected"
      });

      if (!confirm.isConfirmed) return;

      try {
        await axios.post("/api/products/bulk-delete", {
          product_ids: this.selectedProductIds
        });

        this.selectedProductIds = [];
        this.loadProducts();
        this.loadStatistics();
        this.loadTagAnalytics();
        this.loadOrphanTags();
        this.showToast("success", "Selected products deleted!");
      } catch (error) {
        this.showToast("error", "Bulk delete failed.");
      }
    },

    async cleanupOrphans() {
      const confirm = await window.Swal.fire({
        title: "Clean Up Unused Tags?",
        text: `This will remove ${this.orphanTagsList.length} orphan tag(s) with zero attached products.`,
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#e11d48",
        cancelButtonColor: "#64748b",
        confirmButtonText: "Clean Up Now"
      });

      if (!confirm.isConfirmed) return;

      try {
        const response = await axios.post("/api/tags/cleanup-orphans");
        this.showToast("success", response.data.message);
        this.loadTagAnalytics();
        this.loadOrphanTags();
      } catch (error) {
        this.showToast("error", "Cleanup failed.");
      }
    },

    async mergeTags() {
      if (this.mergeSourceId === this.mergeTargetId) {
        this.showToast("warning", "Source and Target tags cannot be the same.");
        return;
      }

      const sourceTag = this.allTagsList.find(t => t.id === Number(this.mergeSourceId));
      const targetTag = this.allTagsList.find(t => t.id === Number(this.mergeTargetId));

      const confirm = await window.Swal.fire({
        title: `Merge '${sourceTag.name}' into '${targetTag.name}'?`,
        text: `All products linked to '${sourceTag.name}' will be transferred to '${targetTag.name}', and '${sourceTag.name}' will be deleted.`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#4f46e5",
        cancelButtonColor: "#64748b",
        confirmButtonText: "Confirm Merge"
      });

      if (!confirm.isConfirmed) return;

      try {
        const response = await axios.post("/api/tags/merge", {
          source_tag_id: this.mergeSourceId,
          target_tag_id: this.mergeTargetId
        });

        this.showToast("success", response.data.message);
        this.mergeSourceId = "";
        this.mergeTargetId = "";
        this.loadProducts();
        this.loadTagAnalytics();
        this.loadOrphanTags();
      } catch (error) {
        this.showToast("error", "Tag merge failed.");
      }
    },

    renderTagChart(tags) {
      const ctx = document.getElementById("tagChart");
      if (!ctx) return;

      if (this.chartInstance) {
        this.chartInstance.destroy();
      }

      const labels = tags.map(t => t.name);
      const data = tags.map(t => t.products_count);
      const bgColors = tags.map(t => t.color || '#3B82F6');

      if (window.Chart) {
        this.chartInstance = new window.Chart(ctx, {
          type: "bar",
          data: {
            labels: labels,
            datasets: [{
              label: "Products Count",
              data: data,
              backgroundColor: bgColors,
              borderRadius: 8,
              borderSkipped: false
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: { display: false }
            },
            scales: {
              y: {
                beginAtZero: true,
                ticks: { stepSize: 1 }
              }
            }
          }
        });
      }
    },

    exportCSV() {
      let csvContent = "data:text/csv;charset=utf-8,ID,SKU,Name,Price,Tags\n";
      this.products.forEach(p => {
        const tagNames = p.tags ? p.tags.map(t => t.name).join("; ") : "";
        csvContent += `"${p.id}","${p.sku}","${p.name}","${p.price}","${tagNames}"\n`;
      });

      const encodedUri = encodeURI(csvContent);
      const link = document.createElement("a");
      link.setAttribute("href", encodedUri);
      link.setAttribute("download", `Products_Export_${new Date().toISOString().slice(0, 10)}.csv`);
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
    },

    exportExcel() {
      let excelContent = "ID\tSKU\tProduct Name\tPrice ($)\tAttached Tags\n";
      this.products.forEach(p => {
        const tagNames = p.tags ? p.tags.map(t => t.name).join(", ") : "";
        excelContent += `${p.id}\t${p.sku}\t${p.name}\t${p.price}\t${tagNames}\n`;
      });

      const blob = new Blob([excelContent], { type: "application/vnd.ms-excel" });
      const link = document.createElement("a");
      link.href = URL.createObjectURL(blob);
      link.download = `Products_Catalog_${new Date().toISOString().slice(0, 10)}.xls`;
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
    },

    exportPrintPDF() {
      window.print();
    }
  }
};
</script>
