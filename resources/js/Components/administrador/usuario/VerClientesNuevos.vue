<script setup>
import { ref, onBeforeMount } from 'vue';
import axios from 'axios';

const newClients = ref([]);

const fetchNewClients = async () => {
  try {
    const response = await axios.get('/api/new-clients');
    newClients.value = response.data;
  } catch (error) {
    console.error('Error fetching new clients:', error);
  }
};

const headers = ref([
  { text: "Código", value: "codigo" },
  { text: "Nombre", value: "nombre_cliente"},
  { text: "Email", value: "correo"},
  { text: "Telefóno", value: "telefono"},
  { text: "Estado", value: "estado"},
  { text: "Opciones", value: "codigo"},
]);

onBeforeMount(() => {
  fetchNewClients();
});
</script>
<template>
    <div>
      <h2 class="text-xl font-bold mb-4">Ver Clientes Nuevos</h2>
      <!-- Aquí puedes agregar la lista de clientes nuevos -->
      <easy-data-table
    :headers="headers"
    :items="newClients"
  />
    </div>
  </template>
  

  
  <style scoped>
  /* Añadir estilos específicos si es necesario */
  </style>
  