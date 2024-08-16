<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

// Variables reactivas
const selectedOperador = ref(null);
const users = ref([]);
const actual = ref([]);

const niveles = ref([
  { label: 'Nivel 2', valor: 0 },
  { label: 'Nivel 4', valor: 0 },
  { label: 'Nivel 6', valor: 0 },
  { label: 'Nivel 8', valor: 0 },
  { label: 'Nivel 10', valor: 0 }
]);

// Cargar los usuarios al montar el componente
onMounted(async () => {
  const response = await axios.get('/users');
  users.value = response.data;
});

// Función para guardar las escalas
const guardarEscalas = async () => {
  const data = {
    operador: selectedOperador.value,
    escalas: niveles.value
  };
  
  try {
    await axios.post('/api/guardar-escalas', data);
    alert('Escalas guardadas exitosamente');
  } catch (error) {
    console.error('Error al guardar escalas:', error);
    alert('Hubo un error al guardar las escalas');
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
          <tr v-for="nivel in niveles" :key="nivel.label">
            <td class="border-b border-gray-200 px-4 py-2">{{ nivel.label }}</td>
            <td class="border-b border-gray-200 px-4 py-2">
              <input type="number" v-model="nivel.valor" class="w-full border border-gray-300 rounded px-3 py-2">
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
