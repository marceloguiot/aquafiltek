<template>
<Head title="Postventa" />

<AuthenticatedLayout>
    <div class="flex flex-col mt-10 w-3/4 mx-auto">
    <h1 class="text-2xl font-semibold mb-4">Registrar Postventa</h1>
    <div class="flex flex-col mx-auto w-full">
      
      <form @submit.prevent="handleSubmit" class="flex flex-col bg-slate-600 rounded-md p-4">
        <div>
        <label for="cliente" class="block text-sm font-semibold mb-1">Cliente</label>
        <select v-model="postventa.id_gestion" @change="fetchClienteNombre" class="w-full px-3 py-2 border rounded-md" required>
          <option value="" disabled selected>Selecciona una gestión</option>
          <option v-for="gestion in gestiones" :key="gestion.id" :value="gestion.id">{{ gestion.id }} - {{ gestion.cliente_nombre }}</option>
        </select>
      </div>
        <div class="flex flex-col">
          <label class="mt-5 text-sm font-semibold mb-1 text-white">Trabajo Realizado</label>
          <div class="flex items-center">
            <label class="inline-flex items-center">
              <input
                type="radio"
                v-model="postventa.trabajo_realizado"
                value="Sí"
                class="form-radio"
              />
              <span class="ml-2 text-white">Sí</span>
            </label>
            <label class="inline-flex items-center">
              <input
                type="radio"
                v-model="postventa.trabajo_realizado"
                value="No"
                class="form-radio ml-2"
              />
              <span class="ml-2 text-white">No</span>
            </label>
          </div>
        </div>
        <div class="mt-5">
          <label class="text-white text-sm font-semibold mb-1">Pago Realizado</label>
          <div class="flex items-center">
            <label class="inline-flex items-center">
              <input
                type="radio"
                v-model="postventa.pago_realizado"
                value="Sí"
                class="form-radio"
              />
              <span class="ml-2 text-white">Sí</span>
            </label>
            <label class="inline-flex items-center">
              <input
                type="radio"
                v-model="postventa.pago_realizado"
                value="No"
                class="form-radio ml-2"
              />
              <span class="ml-2 text-white">No</span>
            </label>
          </div>
        </div>
        <div class="mt-5">
          <label for="fecha_ejecucion" class="text-white mt-5 text-sm font-semibold mb-1">Fecha de Ejecución</label>
          <input
            type="date"
            id="fecha_ejecucion"
            v-model="postventa.fecha_ejecucion"
            class="w-full px-3 py-2 border rounded-md"
            required
          />
        </div>
        <div class="mt-5">
          <label for="informe" class="text-white text-sm font-semibold mb-1">Informe (PDF)</label>
          <input
            type="file"
            id="informe"
            @change="handleFileUpload"
            accept="application/pdf"
            class="w-full px-3 py-2 border rounded-md text-white"
            required
          />
        </div>
        <button
          type="submit"
          class="mt-6 px-4 py-2 bg-blue-500 text-white font-semibold rounded-md hover:bg-blue-600"
        >
          Registrar
        </button>
      </form>
    </div>
</div>
</AuthenticatedLayout>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import axios from 'axios';
  import { Head } from '@inertiajs/vue3';
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  
  const postventa = ref({
    trabajo_realizado: '',
    pago_realizado: '',
    fecha_ejecucion: '',
    id_gestion: '',
    informe: null
  });
  
  const handleFileUpload = (event) => {
    postventa.value.informe = event.target.files[0];
  };

  const gestiones = ref([]);
  const clienteNombre = ref('');

  const fetchGestiones = async () => {
  try {
    const response = await axios.get('/gestiones-aceptadas');
    gestiones.value = response.data;
  } catch (error) {
    console.error('Error al obtener las gestiones:', error);
  }
};

  const fetchClienteNombre = async () => {
  if (postventa.value.id_gestion) {
    try {
      const response = await axios.get(`/cliente-nombre/${postventa.value.id_gestion}`);
      clienteNombre.value = response.data.nombre_cliente;
      console.log('se llego');
    } catch (error) {
      console.error('Error al obtener el nombre del cliente:', error);
    }
  }
};
  
  const handleSubmit = async () => {
    const formData = new FormData();
    formData.append('trabajo_realizado', postventa.value.trabajo_realizado);
    formData.append('pago_realizado', postventa.value.pago_realizado);
    formData.append('fecha_ejecucion', postventa.value.fecha_ejecucion);
    formData.append('informe', postventa.value.informe);
    formData.append('id_gestion', postventa.value.id_gestion);
  
    try {
      const response = await axios.post('/postventa', formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      });
      console.log('Postventa registrada:', response.data);
    } catch (error) {
      console.error('Error al registrar la postventa:', error);
    }
  };

  onMounted(fetchGestiones);
  </script>
  
  <style scoped>
  /* Add any additional styling if necessary */
  </style>
  