<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Vue Select2</title>
    
    <!-- Load jQuery and Select2 from CDN -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <!-- Load Vue from CDN -->
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    
    <style>
        /* Your styles here */
        body {
            font-family: sans-serif;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div id="app">
        <h1>Laravel 12 + Vue.js + Select2</h1>
        
        <div style="margin: 20px 0;">
            <select id="tags-select" multiple style="width: 100%; padding: 10px;">
                <option value="1">Option 1</option>
                <option value="2">Option 2</option>
                <option value="3">Option 3</option>
                <option value="4">Option 4</option>
            </select>
        </div>
        
        <button onclick="alert('Button clicked!')">Test Button</button>
    </div>
    
    <script>
        // Initialize Select2
        $(document).ready(function() {
            $('#tags-select').select2({
                placeholder: 'Select options...',
                allowClear: true,
                multiple: true,
                width: '100%'
            });
            
            console.log('Select2 initialized successfully');
        });
        
        // Simple Vue app (optional)
        const { createApp } = Vue;
        
        createApp({
            data() {
                return {
                    message: 'Hello Vue!'
                }
            }
        }).mount('#app');
    </script>
</body>
</html>