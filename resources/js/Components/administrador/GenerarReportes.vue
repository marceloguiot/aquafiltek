<template>
  <div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-6">Generar Reportes</h1>

    <!-- Formulario Historial Usuario -->
    <div class="mb-8 p-6 bg-slate-400 shadow rounded-lg">
      <h2 class="text-xl font-semibold mb-4">Reporte Historial Usuario</h2>
      <form @submit.prevent="generarReporteHistorialUsuario">
        <div class="mb-4">
          <label for="fechaInicio" class="block text-gray-700">Fecha de inicio</label>
          <input type="date" v-model="historialUsuario.fechaInicio" class="mt-1 block w-full border rounded px-3 py-2" required>
        </div>

        <div class="mb-4">
          <label for="fechaFin" class="block text-gray-700">Fecha de fin</label>
          <input type="date" v-model="historialUsuario.fechaFin" class="mt-1 block w-full border rounded px-3 py-2" required>
        </div>

        <div class="mb-4">
          <label for="operador" class="block text-gray-700">Seleccionar operador</label>
          <select v-model="historialUsuario.operador" class="mt-1 block w-full border rounded px-3 py-2" required>
            <option v-for="user in users" :key="user.id" :value="user.id">{{ user.name }}</option>
          </select>
        </div>

        <div class="mb-4">
          <label for="ordenarPor" class="block text-gray-700">Ordenar por</label>
          <select v-model="historialUsuario.ordenarPor" class="mt-1 block w-full border rounded px-3 py-2" required>
            <option value="cliente">Cliente</option>
            <option value="resultado_gestion">Resultado de gestión</option>
            <option value="duracion_gestion">Duración de gestión</option>
            <option value="fecha">Fecha</option>
          </select>
        </div>

        <div class="mb-4">
          <label for="formato" class="block text-gray-700">Formato de exportación</label>
          <select v-model="historialUsuario.formato" class="mt-1 block w-full border rounded px-3 py-2" required>
            <option value="pdf">PDF</option>
            <option value="excel">Excel</option>
          </select>
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
          Generar reporte
        </button>
      </form>
    </div>

    <!-- Formulario Reporte Ejecución del Servicio -->
    <div class="p-6 bg-slate-400 shadow rounded-lg">
      <h2 class="text-xl font-semibold mb-4">Reporte Ejecución del Servicio</h2>
      <form @submit.prevent="generarReporteEjecucion">
        <div class="mb-4">
          <label for="fechaInicioEjecucion" class="block text-gray-700">Fecha de inicio</label>
          <input type="date" v-model="reporteEjecucion.fechaInicio" class="mt-1 block w-full border rounded px-3 py-2" required>
        </div>

        <div class="mb-4">
          <label for="fechaFinEjecucion" class="block text-gray-700">Fecha de fin</label>
          <input type="date" v-model="reporteEjecucion.fechaFin" class="mt-1 block w-full border rounded px-3 py-2" required>
        </div>

        <div class="mb-4">
          <label for="formatoEjecucion" class="block text-gray-700">Formato de exportación</label>
          <select v-model="reporteEjecucion.formato" class="mt-1 block w-full border rounded px-3 py-2" required>
            <option value="pdf">PDF</option>
            <option value="excel">Excel</option>
          </select>
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
          Generar reporte
        </button>
      </form>
    </div>

    <div class="p-6 bg-slate-400 mt-6 shadow rounded-lg">
    <form @submit.prevent="generarClientesNoGestionados">
      <h2 class="text-xl font-bold mb-4">Reporte Clientes No Gestionados</h2>
      <div class="mb-4">
        <label for="formato_no_gestionados" class="block text-gray-700">Exportar como</label>
        <select v-model="clientesNoGestionados.formato" class="mt-1 block w-full" required>
          <option value="pdf">PDF</option>
          <option value="excel">Excel</option>
        </select>
      </div>
      <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Generar Reporte</button>
    </form>
  </div>


  </div>
</template>

<script setup>
import { ref } from 'vue';

const users = ref([
  { id: 1, name: 'Operador 1' },
  { id: 2, name: 'Operador 2' },
  // Agrega más usuarios según sea necesario
]);

const historialUsuario = ref({
  fechaInicio: '',
  fechaFin: '',
  operador: '',
  ordenarPor: '',
  formato: 'pdf',
});

const reporteEjecucion = ref({
  fechaInicio: '',
  fechaFin: '',
  formato: 'pdf',
});

const clientesNoGestionados = ref({
  formato: 'pdf',
});


const generarReporteHistorialUsuario = () => {
  // Aquí se puede hacer la lógica para generar el reporte historial usuario
  console.log('Generando reporte historial usuario:', historialUsuario.value);
};

const generarReporteEjecucion = () => {
  // Aquí se puede hacer la lógica para generar el reporte de ejecución del servicio
  console.log('Generando reporte de ejecución del servicio:', reporteEjecucion.value);
};
</script>

<style scoped>
/* Puedes agregar estilos personalizados aquí si es necesario */
</style>
