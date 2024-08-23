<script setup>
import { ref, onBeforeMount } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';

const clients = ref([]);
const clients_archivados = ref([]);

const ver_archivados = ref(false);
const ver_inactivos = ref(true);

const fetchInactiveClients = async () => {
  try {
    const response = await axios.get('/api/inactive-clients');
    clients.value = response.data;
  } catch (error) {
    console.error('Error fetching inactive clients:', error);
  }
};

const fetchArchivedClients = async () => {
  try {
    const response = await axios.get('/api/archived-clients');
    clients_archivados.value = response.data;
  } catch (error) {
    console.error('Error fetching archived clients:', error);
  }
};



const reactivar = async (codigo) => {
  try {
        const response = await axios.post('/cliente/reactivar', {
            codigo: codigo,
        });
        if (response.data.message == 'exito') {
          Swal.fire({
      title: "¡Cliente reactivado!",
      text: "El cliente fue reactivado correctamente.",
      confirmButtonText: "Aceptar",
      icon: "success"
    });
        } else {
          Swal.fire({
      title: "¡Cliente no reactivado!",
      text: "Ocurrio un error, favor de reintentar más tarde.",
      confirmButtonText: "Aceptar",
      icon: "error"
    });
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

const headers_archivados = ref([
  { text: "Código", value: "codigo" },
  { text: "Nombre", value: "nombre_cliente"},
  { text: "Motivo", value: "motivo"},
  { text: "Tipo", value: "tipo_desc"},
  { text: "Fecha", value: "fecha"},
  { text: "Operador", value: "operador_name"},
  { text: "Opciones", value: "opciones"},
]);


const mostrar_archivados = () => {
  ver_archivados.value = true;
  ver_inactivos.value = false;
}

const mostrar_inactivos = () => {
  ver_archivados.value = false;
  ver_inactivos.value = true;
}

const desarchivar = async (codigo) => {
  try {
  const response = await axios.post('/cliente/desarchivar', {
            id_cliente: codigo,
        });
        if (response.data.message == 'exito') {
          Swal.fire({
      title: "¡Cliente desarchivado!",
      text: "El cliente fue desarchivado correctamente.",
      confirmButtonText: "Aceptar",
      icon: "success"
    });
        } else {
          Swal.fire({
      title: "¡Cliente no desarchivado!",
      text: "Ocurrio un error, favor de reintentar más tarde.",
      confirmButtonText: "Aceptar",
      icon: "error"
    });
        }
        fetchInactiveClients();
        fetchArchivedClients();
    } catch (error) {
        console.error('Error during inactivation registration:', error);
    }
} 

const archivar = async(codigo) =>{
  try {
  const response = await axios.post('/cliente/archivar', {
            id_cliente: codigo,
        });
        if (response.data.message == 'exito') {
          Swal.fire({
      title: "¡Cliente archivado!",
      text: "El cliente fue archivado correctamente.",
      confirmButtonText: "Aceptar",
      icon: "success"
    });
        } else {
          Swal.fire({
      title: "¡Cliente no archivado!",
      text: "Ocurrio un error, favor de reintentar más tarde.",
      confirmButtonText: "Aceptar",
      icon: "error"
    });
        }
        fetchInactiveClients();
        fetchArchivedClients();
    } catch (error) {
        console.error('Error during inactivation registration:', error);
    }
}

onBeforeMount(() => {
  fetchInactiveClients();
  fetchArchivedClients();
});

</script>
<template>
    <div v-if="ver_inactivos">
      <h2 class="text-xl font-bold mb-4">Inactivos</h2>
      <div class="flex justify-end">
        <button class="bg-sky-600 mb-4 rounded-md w-[150px] py-2 px-3 font-semibold text-white hover:bg-sky-500" @click="mostrar_archivados">Ver archivados</button>
      </div>
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
    <div v-if="ver_archivados">
      <h2 class="text-xl font-bold mb-4">Archivados</h2>
      <div class="flex justify-end">
        <button class="bg-sky-600 mb-4 rounded-md w-[150px] py-2 px-3 font-semibold text-white hover:bg-sky-500" @click="mostrar_inactivos">Ver inactivos</button>
      </div>
      <easy-data-table
    :headers="headers_archivados"
    :items="clients_archivados"
  >
  <template #item-opciones="id">
    <div class="flex flex-col">
    <button class="bg-teal-500 rounded-md px-2 py-1 font-semibold text-white hover:bg-teal-400 mt-2 w-[85px] mx-auto mb-1" @click="desarchivar(id.codigo)">Desarchivar</button>
  </div>
  </template>
  </easy-data-table>
    </div>
  </template>

  
  <style scoped>
  /* Añadir estilos específicos si es necesario */
  </style>
  