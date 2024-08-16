<script setup>
import { ref } from 'vue';

const tiraPermanente = ref('no');
const mensaje = ref('');
const tiempo = ref('');

const submitForm = () => {
  // Lógica para manejar el envío del formulario
  console.log({
    tiraPermanente: tiraPermanente.value,
    mensaje: mensaje.value,
    tiempo: tiempo.value,
  });
};
</script>

<template>
  <div class="w-1/2 mx-auto bg-slate-100 p-8 rounded shadow">
    <h2 class="text-xl font-bold mb-6">Administración de tira informativa</h2>

    <form @submit.prevent="submitForm">
      <div class="mb-4">
        <label for="tiraPermanente" class="block text-gray-700 font-bold mb-2">Tira permanente</label>
        <select v-model="tiraPermanente" id="tiraPermanente" class="block w-full mt-1 rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
          <option value="si">Sí</option>
          <option value="no">No</option>
        </select>
      </div>

      <div class="mb-4">
        <label for="mensaje" class="block text-gray-700 font-bold mb-2">Mensaje</label>
        <textarea v-model="mensaje" id="mensaje" rows="4" class="block w-full mt-1 rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
      </div>

      <div class="mb-4">
        <label for="tiempo" class="block text-gray-700 font-bold mb-2">Tiempo (en minutos)</label>
        <input type="number" v-model="tiempo" id="tiempo" class="block w-full mt-1 rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" min="1">
      </div>

      <button type="submit" class="bg-blue-500 text-white font-bold py-2 px-4 rounded hover:bg-blue-700 focus:outline-none focus:shadow-outline">
        Guardar
      </button>
    </form>
  </div>
</template>

<style scoped>
/* Estilos personalizados, si los necesitas */
</style>
