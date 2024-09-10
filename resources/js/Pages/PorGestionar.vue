<template>
     <Head title="Por gestionar" />

<AuthenticatedLayout>
    <div class="flex flex-col w-5/6  mx-auto mt-10">
      <h1 class="text-lg font-bold mb-4">Clientes Por Gestionar</h1>
  
      <div v-if="loading" class="text-center">Cargando clientes...</div>
  
      <table v-else class="min-w-full border-collapse border border-gray-300">
        <thead>
          <tr class="bg-slate-800 text-white ">
            <th class="border p-2">Código</th>
            <th class="border p-2">Nombre</th>
            <th class="border p-2">Dirección</th>
            <th class="border p-2">Estado</th>
            <th class="border p-2">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="cliente in clientes" :key="cliente.id" class="bg-white">
            <td class="border p-2 text-center">{{ cliente.id }}</td>
            <td class="border p-2">{{ cliente.nombre_cliente }}</td>
            <td class="border p-2">{{ cliente.direccion }}</td>
            <td class="border p-2">{{ cliente.estado }}</td>
            <td class="border p-2 text-center">
              <!-- Aquí podrías agregar botones de acción, como editar o eliminar -->
              <button class="bg-blue-500 text-white px-2 py-1 rounded">Gestionar</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    </AuthenticatedLayout>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import axios from 'axios';
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  import { Head } from '@inertiajs/vue3';
  
  // Definir estados reactivamente
  const clientes = ref([]);
  const loading = ref(true);
  
  // Función para obtener los clientes por gestionar
  const fetchClientesPorGestionar = async () => {
    try {
      const response = await axios.get('/clientes-por-gestionar');
      clientes.value = response.data;
    } catch (error) {
      console.error('Error al obtener los clientes:', error);
    } finally {
      loading.value = false;
    }
  };
  
  // Llamar a la función al montar el componente
  onMounted(fetchClientesPorGestionar);
  </script>
  
  <style scoped>
  /* Estilos específicos del componente */
  </style>
  