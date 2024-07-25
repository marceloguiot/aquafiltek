<script setup>
import { ref } from 'vue';
import axios from 'axios';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import DataTable from 'datatables.net-vue3';
import Select from 'datatables.net-select';
import jsPDF from 'jspdf';
import 'jspdf-autotable';
import * as XLSX from 'xlsx';
 
DataTable.use(Select);

const startDate = ref('');
const endDate = ref('');
const reportData = ref([]);
const registrosPorOperador = ref([]);

const columns = [
  { title: 'Código', data: 'codigo' },
  { title: 'Cliente', data: 'nombre_cliente' },
  { title: 'Fecha Acepto', data: 'fecha_acepto' },
  { title: 'Hora Acepto', data: 'hora_acepto' },
  { title: 'Precio', data: 'precio' },
  { title: 'Comentarios', data: 'comentarios' },
  { title: 'Fecha Gestión', data: 'fecha_gestion' },
  { title: 'Operador', data: 'nombre_operador' },
];

const options = {
    language:{
        url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-MX.json'
    }
};
const generateReport = async () => {
  try {
    const response = await axios.post('/getReport', {
      start_date: startDate.value,
      end_date: endDate.value,
    });
    reportData.value = await response.data.registros;
    registrosPorOperador.value = await response.data.registrosPorOperador;
    console.log(reportData.value);
  } catch (error) {
    console.error('Error generating report:', error);
  }
};

const exportToPDF = () => {
  const doc = new jsPDF();
  doc.autoTable({
    head: [columns.map(col => col.title)],
    body: reportData.value.map(row => columns.map(col => row[col.data])),
  });
  doc.save('reporte.pdf');
};

const exportToExcel = () => {
  const worksheet = XLSX.utils.json_to_sheet(reportData.value);
  const workbook = XLSX.utils.book_new();
  XLSX.utils.book_append_sheet(workbook, worksheet, 'Reporte');
  XLSX.writeFile(workbook, 'reporte.xlsx');
};
</script>
<template>
        <Head title="Reportes" />

<AuthenticatedLayout>
    <div class="max-w-[85%] mx-auto p-4 mt-10">
      <h1 class="text-2xl font-light mb-4">Reporte de ejecución del servicio - mantenimiento de cisterna</h1>
      <form @submit.prevent="generateReport" class="space-y-4 bg-slate-600 py-4 px-3 rounded-md ">
        <div class="flex flex-col">
          <div class="flex flex-col md:flex-1">
            <label for="start_date" class="text-sm font-semibold text-white mb-1">Fecha de Inicio</label>
            <input
              type="date"
              id="start_date"
              v-model="startDate"
              class="px-3 py-2 border rounded-md"
              required
            />
          </div>
          <div class="flex flex-col md:flex-1 mt-5">
            <label for="end_date" class="text-sm font-semibold text-white mb-1">Fecha de Fin</label>
            <input
              type="date"
              id="end_date"
              v-model="endDate"
              class="px-3 py-2 border rounded-md"
              required
            />
          </div>
        </div>
        <button
          type="submit"
          class="mt-4 px-4 py-2 bg-blue-500 text-white font-semibold rounded-md hover:bg-blue-400"
        >
          Generar Reporte
        </button>
      </form>
  
      <div v-if="reportData.length > 0" class="mt-6">
        <h2 class="text-xl font-bold mb-4">Resultados del Reporte</h2>
        <DataTable :data="reportData" :columns="columns" :options="options" class="display">
    </DataTable>
    <div class="mt-4 flex space-x-4">
        <button @click="exportToPDF" class="flex items-center px-4 py-2 bg-red-500 text-white font-semibold rounded-md hover:bg-red-600">
          <i class="fas fa-file-pdf mr-2"></i> Descargar PDF
        </button>
        <button @click="exportToExcel" class="flex items-center px-4 py-2 bg-green-500 text-white font-semibold rounded-md hover:bg-green-600">
          <i class="fas fa-file-excel mr-2"></i> Descargar Excel
        </button>
        
      </div>
      <h2 class="text-xl font-bold mt-10 mb-4">Registros por Operador</h2>
      <table class="min-w-full bg-white border border-gray-200">
        <thead>
          <tr>
            <th class="px-4 py-2 border border-slate-600 bg-slate-400">ID Operador</th>
            <th class="px-4 py-2 border border-slate-600 bg-slate-400">Nombre Operador</th>
            <th class="px-4 py-2 border border-slate-600 bg-slate-400">Cantidad de Registros</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="operador in registrosPorOperador" :key="operador.id_operador">
            <td class="px-4 py-2 border border-slate-600 text-center">{{ operador.id_operador }}</td>
            <td class="px-4 py-2 border border-slate-600">{{ operador.nombre_operador }}</td>
            <td class="px-4 py-2 border border-slate-600 text-center">{{ operador.cantidad }}</td>
          </tr>
        </tbody>
      </table>
      </div>
    </div>
</AuthenticatedLayout>
  </template>
  <style>
  @import 'datatables.net-dt';
  </style>
