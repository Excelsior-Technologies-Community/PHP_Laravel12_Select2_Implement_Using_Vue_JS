<template>
  <div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Simple Product Manager with Select2</h1>
    
    <!-- Product Form -->
    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
      <h2 class="text-lg font-semibold mb-4">Add New Product</h2>
      
      <form @submit.prevent="addProduct" class="space-y-4">
        <div>
          <label class="block mb-1">Product Name</label>
          <input v-model="newProduct.name" type="text" class="w-full p-2 border rounded" required>
        </div>
        
        <div>
          <label class="block mb-1">Price ($)</label>
          <input v-model="newProduct.price" type="number" step="0.01" class="w-full p-2 border rounded" required>
        </div>
        
        <div>
          <label class="block mb-1">Tags (Select2 Multiple)</label>
          <select id="tags-select" multiple class="w-full p-2 border rounded">
            <option v-for="tag in tags" :key="tag.id" :value="tag.id">{{ tag.name }}</option>
          </select>
        </div>
        
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
          Add Product
        </button>
      </form>
    </div>
    
    <!-- Products List -->
    <div class="bg-white p-6 rounded-lg shadow-md">
      <h2 class="text-lg font-semibold mb-4">Products</h2>
      
      <div v-if="products.length === 0" class="text-gray-500">
        No products yet. Add your first product!
      </div>
      
      <div v-for="product in products" :key="product.id" class="border-b py-4 last:border-b-0">
        <div class="flex justify-between items-center">
          <div>
            <h3 class="font-medium">{{ product.name }}</h3>
            <p class="text-gray-600">${{ product.price }}</p>
            <div class="mt-2">
              <span v-for="tag in product.tags" :key="tag.id" 
                    class="inline-block bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded mr-1">
                {{ tag.name }}
              </span>
            </div>
          </div>
          <button @click="deleteProduct(product.id)" 
                  class="text-red-500 hover:text-red-700">
            Delete
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'App',
  data() {
    return {
      tags: [],
      products: [],
      newProduct: {
        name: '',
        price: '',
        tags: []
      }
    };
  },
  mounted() {
    this.loadTags();
    this.loadProducts();
    
    // Initialize Select2 after Vue is mounted
    this.$nextTick(() => {
      this.initSelect2();
    });
  },
  methods: {
    async loadTags() {
      try {
        const response = await axios.get('/api/tags');
        this.tags = response.data;
      } catch (error) {
        console.error('Error loading tags:', error);
      }
    },
    
    async loadProducts() {
      try {
        const response = await axios.get('/api/products');
        this.products = response.data;
      } catch (error) {
        console.error('Error loading products:', error);
      }
    },
    
    initSelect2() {
      // Initialize Select2 on the tags select element
      $('#tags-select').select2({
        placeholder: 'Select tags...',
        allowClear: true,
        multiple: true
      });
      
      // Update Vue data when Select2 changes
      $('#tags-select').on('change', () => {
        this.newProduct.tags = $('#tags-select').val() || [];
      });
    },
    
    async addProduct() {
      try {
        const response = await axios.post('/api/products', {
          name: this.newProduct.name,
          price: parseFloat(this.newProduct.price),
          tags: this.newProduct.tags
        });
        
        // Add new product to list
        this.products.push(response.data);
        
        // Reset form
        this.newProduct = {
          name: '',
          price: '',
          tags: []
        };
        
        // Clear Select2
        $('#tags-select').val(null).trigger('change');
        
        alert('Product added successfully!');
      } catch (error) {
        console.error('Error adding product:', error);
        alert('Error adding product. Please try again.');
      }
    },
    
    async deleteProduct(id) {
      if (!confirm('Are you sure you want to delete this product?')) return;
      
      try {
        await axios.delete(`/api/products/${id}`);
        this.products = this.products.filter(p => p.id !== id);
        alert('Product deleted successfully!');
      } catch (error) {
        console.error('Error deleting product:', error);
        alert('Error deleting product.');
      }
    }
  }
};
</script>