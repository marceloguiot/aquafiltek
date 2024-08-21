<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2';

// Variables reactivas
const selectedOperador = ref(null);
const users = ref([]);
const actual = ref([]);
const error = ref(null);

const escalas = ref([
    { escala_id: 2, numero: 0 },
    { escala_id: 4, numero: 0 },
    { escala_id: 6, numero: 0 },
    { escala_id: 8, numero: 0 },
    { escala_id: 10, numero: 0 },
]);

// Cargar los usuarios al montar el componente
onMounted(async () => {
  const response = await axios.get('/users');
  users.value = response.data;
});

const validateForm = () => {
  if (!selectedOperador.value) {
    error.value = 'Seleccione un operador.';
    return false;
  }
  if (escalas.value.some((nivel) => nivel.numero === 0)) {
    error.value = 'Todos los niveles deben tener un número mayor a 0.';
    return false;
  }
  error.value = null;
  return true;
};

// Función para guardar las escalas
const guardarEscalas = async () => {
  const data = {
    usuario_id: selectedOperador.value,
    escalas: escalas.value
  };
  if (!validateForm()){

    Swal.fire({
      title: "¡Error!",
      text: error.value,
      confirmButtonText: "Aceptar",
      icon: "error"
    });
    return;
  }

  try {
    alert(data.usuario_id);
    await axios.post('/guardar-escalas', data);
    Swal.fire({
      title: "¡Escalas actualizadas!",
      text: "Las escalas se almacenaron correctamente.",
      confirmButtonText: "Aceptar",
      icon: "success"
    });
  } catch (error) {
    console.error('Error al guardar escalas:', error);
    Swal.fire({
      title: "¡Error!",
      text: "No se ha podido guardar la información de escalas.",
      confirmButtonText: "Aceptar",
      icon: "error"
    });
  }
};
</script>
<template>
  <div class="p-6 bg-white rounded shadow-md">
    <h2 class="text-xl font-semibold mb-4">Modificar escalas a operadores</h2>

    <!-- Formulario para seleccionar operador -->
    <div class="mb-6">
      <label for="operador" class="block text-gray-700 font-medium mb-2">Seleccionar operador</label>
      <select v-model="selectedOperador" class="block w-full border border-gray-300 rounded px-3 py-2">
        <option v-for="user in users" :key="user.id" :value="user.id">
          {{ user.name }}
        </option>
      </select>
    </div>

    <!-- Tabla para modificar escalas -->
    <div>
      <table class="w-1/2 mx-auto text-left border-collapse">
        <thead>
          <tr>
            <th class="border-b border-gray-200 px-4 py-2 text-white bg-gray-800 w-1/2">Escala</th>
            <th class="border-b border-gray-200 px-4 py-2 text-white bg-gray-800">Número</th>
          </tr>
        </thead>
        <tbody class="bg-slate-200">
          <tr v-for="nivel in escalas" :key="nivel.label">
            <td class="border-b border-gray-200 px-4 py-2">Nivel {{ nivel.escala_id }}</td>
            <td class="border-b border-gray-200 px-4 py-2">
              <input type="number" v-model="nivel.numero" class="w-full border border-gray-300 rounded px-3 py-2">
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Botón para guardar -->
    <div class="mt-4 text-center">
      <button @click="guardarEscalas" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        Guardar Escalas
      </button>
    </div>
  </div>
</template>



<style scoped>
table th, table td {
  text-align: left;
  padding: 0.5rem;
}

table th {
  background-color: #1F2937; /* Tailwind gray-800 */
  color: white;
}

table td input {
  width: 100%;
}
</style>
