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

const reactivar = async (codigo) => {
  try {
        const response = await axios.post('/cliente/reactivar', {
            codigo: codigo,
        });
        if (response.data.success) {
            console.log('Reactivation realizada exitosamente:', response.data.data);
        } else {
            console.error('No se pudo reactivar:', response.data.message);
        }

       fetchInactiveClients();
    } catch (error) {
        console.error('No se pudo reactivar:', error);
    }
}

const headers = ref([
  { text: "Código", value: "codigo" },
  { text: "Nombre", value: "nombre_cliente"},
  { text: "Motivo", value: "motivo"},
  { text: "Tipo", value: "tipo_desc"},
  { text: "Fecha", value: "fecha"},
  { text: "Operador", value: "operador_name"},
  { text: "Opciones", value: "opciones"},
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
  >
  <template #item-opciones="id">
    <div class="flex flex-col">
    <button class="bg-teal-500 rounded-md px-2 py-1 font-semibold text-white hover:bg-teal-400 mt-2 w-[85px] mx-auto" @click="reactivar(id.codigo)">Reactivar</button>
    <button class="bg-yellow-500 rounded-md px-2 py-1 text-white font-semibold hover:bg-yellow-400 mt-2 mb-2 w-[85px] mx-auto" @click="archivar(id.codigo)">Archivar</button>
  </div>
  </template>
  </easy-data-table>
    
    </div>
  </template>

  
  <style scoped>
  /* Añadir estilos específicos si es necesario */
  </style>
  