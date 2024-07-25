<template>
            <Head title="Busqueda" />
<AuthenticatedLayout>
    <div class="max-w-[85%] mx-auto p-4 mt-10">
    <h1 class="text-2xl font-bold mb-4">Buscar por Filtros</h1>
    
    
      <div class="max-w-[85%] mx-auto p-4 mt-10 bg-slate-600 rounded-md px-4">
      <form @submit.prevent="applyFilters" class="space-y-4">
        <div class="flex flex-col">
          <div class="flex flex-col md:flex-1">
            <label for="start_date" class="text-sm font-semibold mb-1">Fecha de Inicio</label>
            <input
              type="date"
              id="start_date"
              v-model="startDate"
              class="px-3 py-2 border rounded-md"
              required
            />
          </div>
          <div class="flex flex-col mt-10">
            <label for="end_date" class="text-sm font-semibold mb-1">Fecha de Fin</label>
            <input
              type="date"
              id="end_date"
              v-model="endDate"
              class="px-3 py-2 border rounded-md"
              required
            />
          </div>
        </div>
        <div class="flex flex-wrap -mx-2">
          <div class="px-2 mb-4 w-1/2 md:w-1/3" v-for="estado in estados" :key="estado.value">
            <label class="inline-flex items-center">
              <input
                type="radio"
                :value="estado.value"
                v-model="selectedEstado"
                class="form-radio"
              />
              <span class="ml-2">{{ estado.label }}</span>
            </label>
          </div>
        </div>
        <button
          type="submit"
          class="mt-4 px-4 py-2 bg-blue-500 text-white font-semibold rounded-md hover:bg-blue-600"
        >
          Aplicar Filtros
        </button>
      </form>
  

    </div>
    <div v-if="results.length > 0" class="mt-6">
        <h2 class="text-xl font-bold mb-4">Resultados de la Búsqueda</h2>
        <DataTable :data="results" :columns="columns" :options="options" class="display">
        </DataTable>
      </div>
</div>
</AuthenticatedLayout>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import axios from 'axios';
  import DataTable from 'datatables.net-vue3';
  import Select from 'datatables.net-select';
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

  DataTable.use(Select);
  
  const startDate = ref('');
  const endDate = ref('');
  const selectedEstado = ref('');
  const results = ref([]);

  const options = {
    language:{
        url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-MX.json'
    }
};
  
  const estados = [
    { label: 'Acepto', value: 'acepto' },
    { label: 'Inspección', value: 'inspección' },
    { label: 'Cobros', value: 'cobros' },
    { label: 'Importante', value: 'importante' },
    { label: 'Reprogramar', value: 'reprogramar' },
    { label: 'Rechazo', value: 'rechazo' },
    { label: 'No Responde', value: 'noresponde' },
    { label: 'Equivocado', value: 'equivocado' },
    { label: 'Averiado', value: 'averiado' },
    { label: 'Otro', value: 'otro' },
    { label: 'Competencia', value: 'competencia' }
  ];
  
  const columns = [
    { title: 'Código', data: 'codigo' },
    { title: 'Fecha', data: 'fecha' },
    { title: 'Hora', data: 'hora' },
    { title: 'Comentarios', data: 'comentarios' },
    { title: 'Tipo', data: 'tipo' },
    { title: 'Fecha Gestión', data: 'fecha_gestion' },
    { title: 'ID Operador', data: 'id_operador' },
    { title: 'Nombre Cliente', data: 'nombre_cliente' },
    { title: 'Nombre Operador', data: 'nombre_operador' },
  ];
  
  const applyFilters = async () => {
    try {
      const response = await axios.post('/getFilteredData', {
        start_date: startDate.value,
        end_date: endDate.value,
        estado: selectedEstado.value,
      });
      results.value = await response.data;
    } catch (error) {
      console.error('Error applying filters:', error);
    }
  };
  </script>
  
  <style>
  @import 'datatables.net-dt';
  </style>
  