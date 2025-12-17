import { createApp } from 'vue';

// Simple Vue app
const App = {
    template: `
        <div>
            <h1>Laravel 12 + Vue.js + Select2</h1>
            <p>Application is loading...</p>
            
            <div style="margin: 20px 0;">
                <select id="tags-select" multiple style="width: 100%;">
                    <option value="1">Option 1</option>
                    <option value="2">Option 2</option>
                    <option value="3">Option 3</option>
                    <option value="4">Option 4</option>
                </select>
            </div>
            
            <button onclick="alert('Test button clicked!')">Test Button</button>
        </div>
    `,
    mounted() {
        // Initialize Select2 after component mounts
        this.initSelect2();
    },
    methods: {
        initSelect2() {
            // Wait a bit to ensure DOM is ready
            setTimeout(() => {
                if (window.$ && window.$.fn.select2) {
                    $('#tags-select').select2({
                        placeholder: 'Select options...',
                        allowClear: true,
                        multiple: true,
                        width: '100%'
                    });
                    console.log('Select2 initialized successfully');
                } else {
                    console.error('jQuery or Select2 not available');
                }
            }, 100);
        }
    }
};

// Create and mount the app
const app = createApp(App);
app.mount('#app');