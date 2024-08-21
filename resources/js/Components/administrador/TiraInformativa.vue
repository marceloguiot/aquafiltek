<script setup>
import { ref } from 'vue';
import Swal from 'sweetalert2';

const tira = ref({
  permanente: '0',  // Default value 'No'
  mensaje: '',
  minutos: ''
});

const submitForm = async () => {
  if(!tira.value.mensaje)
{
  Swal.fire({
      title: "¡Error!",
      text: 'El mensaje no puede estar vacio.',
      confirmButtonText: "Aceptar",
      icon: "error"
    });
  return;
}
if(!tira.value.minutos)
{
  Swal.fire({
      title: "¡Error!",
      text: 'Específique el tiempo.',
      confirmButtonText: "Aceptar",
      icon: "error"
    });
  return;
}

  try {
    const response = await axios.post('/tira-informativa', tira.value);
    console.log('Tira Informativa guardada:', response.data);
    Swal.fire({
      title: "¡Tira informativa guardada!",
      text: "Tira Informativa guardada correctamente.",
      confirmButtonText: "Aceptar",
      icon: "success"
    });
    // Optionally reset the form or provide feedback to the user
  } catch (error) {
    console.error('Error al guardar la Tira Informativa:', error);
  }
};
</script>

<template>
  <div class="w-1/2 mx-auto bg-slate-100 p-8 rounded shadow">
    <h2 class="text-xl font-bold mb-6">Administración de tira informativa</h2>

    <form @submit.prevent="submitForm">
      <div class="mb-4">
        <label for="tiraPermanente" class="block text-gray-700 font-bold mb-2">Tira permanente</label>
        <select v-model="tira.permanente" id="tiraPermanente" class="block w-full mt-1 rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
          <option value="1">Sí</option>
          <option value="0">No</option>
        </select>
      </div>

      <div class="mb-4">
        <label for="mensaje" class="block text-gray-700 font-bold mb-2">Mensaje</label>
        <textarea v-model="tira.mensaje" id="mensaje" rows="4" class="block w-full mt-1 rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
      </div>

      <div class="mb-4">
        <label for="tiempo" class="block text-gray-700 font-bold mb-2">Tiempo (en minutos)</label>
        <input type="number" v-model="tira.minutos" id="tiempo" class="block w-full mt-1 rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" min="1">
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
