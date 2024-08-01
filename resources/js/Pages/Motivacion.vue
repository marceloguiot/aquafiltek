<template>
              <Head title="Motivate" />

<AuthenticatedLayout>
    <div class="flex flex-col items-center mt-10">
      <div class="mb-4 p-4 bg-gray-100 rounded shadow-lg">
        <p class="text-xl font-semibold text-gray-700">{{ motivationalQuote }}</p>
      </div>
      <button @click="fetchMotivationalQuote" class="mt-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        Load New Quote
      </button>
    </div>
</AuthenticatedLayout>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import axios from 'axios';
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  import { Head } from '@inertiajs/vue3';
  
  // Ref to hold the motivational quote
  const motivationalQuote = ref('');
  
  // Function to fetch a new motivational quote
  const fetchMotivationalQuote = async () => {
    try {
      const response = await axios.get('https://api.quotable.io/random'); // Replace with the actual API URL
      motivationalQuote.value = response.data.content; // Adjust according to the API response structure
    } catch (error) {
      console.error('Error fetching motivational quote:', error);
    }
  };
  
  // Fetch a quote when the component is mounted
  onMounted(fetchMotivationalQuote);
  </script>
  
  <style scoped>
  /* Add any styles you need here */
  </style>
  