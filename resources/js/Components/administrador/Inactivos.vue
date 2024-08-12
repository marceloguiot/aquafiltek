<script setup>
import { ref, onBeforeMount } from 'vue';
import axios from 'axios';

const clients = ref([]);

const fetchInactiveClients = async () => {
  try {
    const response = await axios.get('/api/inactive-clients');
    clients.value = response.data;
  } catch (error) {
    console.error('Error fetching inactive clients:', error);
  }
};

const headers = ref([
  { text: "Código", value: "codigo" },
  { text: "Nombre", value: "nombre_cliente"},
  { text: "Motivo", value: "idcanton"},
  { text: "Tipo", value: "tipo_desc"},
  { text: "Fecha", value: "fecha"},
  { text: "Operador", value: "name"},
]);

onBeforeMount(() => {
  fetchInactiveClients();
});

</script>
<template>
    <div>
      <h2 class="text-xl font-bold mb-4">Inactivos</h2>
      <!-- Aquí puedes agregar la gestión de usuarios inactivos -->
      <easy-data-table
    :headers="headers"
    :items="clients"
  />
    
    </div>
  </template>

  
  <style scoped>
  /* Añadir estilos específicos si es necesario */
  </style>
  