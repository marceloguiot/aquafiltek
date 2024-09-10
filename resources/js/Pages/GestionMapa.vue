<template>
        <Head title="Tablero" />

<AuthenticatedLayout>
    <div class="flex flex-col w-5/6 mx-auto">
    <h1 class="text-lg font-bold mb-4 mt-6">Gestión por mapa</h1>
    <div class="map-container">
      <div ref="map" class="map"></div>
    </div>
    </div>
    </AuthenticatedLayout>
  </template>
  
  <script setup>
  import { onMounted, ref } from 'vue';
  import { Loader } from '@googlemaps/js-api-loader';
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  import { Head } from '@inertiajs/vue3';
  
  const map = ref(null);
  
  const initMap = () => {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(position => {
        const { latitude, longitude } = position.coords;
        const mapOptions = {
          center: { lat: latitude, lng: longitude },
          zoom: 12,
        };
        map.value = new google.maps.Map(document.querySelector('.map'), mapOptions);
      });
    } else {
      console.error('Geolocation is not supported by this browser.');
    }
  };
  
  onMounted(() => {
    const loader = new Loader({
      apiKey: 'AIzaSyB1pBMfBjAmmtfqzCwQAa_6TOjq3Eq_jB4',
      version: 'weekly',
    });
  
    loader.load().then(() => {
      initMap();
    });
  });
  </script>
  
  <style scoped>
  .map-container {
    width: 100%;
    height: 100vh;
  }
  
  .map {
    margin: 0px auto;
    margin-top: 20px;
    width: 60%;
    height: 60%;
  }
  </style>
  