<template>
    <div class="p-4">
      <h2 class="text-2xl font-bold mb-4">Horas Ocupadas de Llamadas Programadas</h2>
      <table class="min-w-full border-collapse border border-gray-300">
        <thead>
          <tr>
            <!-- Encabezados de las horas -->
            <th
              v-for="hora in horas"
              :key="hora"
              class="border border-gray-300 px-2 text-sm py-1 text-center"
            >
              {{ hora }}
            </th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <!-- Filas de minutos -->
            <td
              v-for="(hora, index) in horas"
              :key="index"
              class="border border-gray-300 text-xs text-center"
            >
              <div
                v-for="minuto in minutos"
                :key="minuto"
                :class="[
                  esHoraOcupada(hora, minuto) ? 'bg-red-500 text-white' : '', 
                  isHoraSeleccionada(hora, minuto) ? 'bg-green-500 text-white' : 'cursor-pointer hover:bg-green-200'
                ]"
                @click="seleccionarHora(hora, minuto)"
              >
                {{ hora }}:{{ minuto }}
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>
  
  <script setup>
  import { ref, watch } from 'vue';
  import axios from 'axios';
  
  const props = defineProps({
    fecha: String,
  });
  
  const emit = defineEmits(['horaSeleccionada']);
  
  const horasOcupadas = ref([]);
  const horaSeleccionada = ref(''); // Almacena la hora seleccionada
  const horas = Array.from({ length: 11 }, (_, i) => String(i + 8).padStart(2, '0')); // Genera las horas de 08 a 18
  const minutos = ['00', '02', '04', '06', '08', '10', '12', '14', '16', '18', '20', '22', '24', '26', '28', '30', '32', '34', '36', '38', '40', '42', '44', '46', '48', '50', '52', '54', '56', '58'];
  
  const fetchScheduledCalls = async () => {
    if (!props.fecha) return;
  
    try {
      const response = await axios.get('/llamadas-programadas', {
        params: { fecha: props.fecha },
      });
      horasOcupadas.value = response.data;
    } catch (error) {
      console.error('Error fetching scheduled calls:', error);
    }
  };
  
  watch(() => props.fecha, fetchScheduledCalls);
  
  const esHoraOcupada = (hora, minuto) => {
    const tiempo = `${hora}:${minuto}:00`;
    return horasOcupadas.value.includes(tiempo);
  };
  
  const isHoraSeleccionada = (hora, minuto) => {
    const tiempo = `${hora}:${minuto}:00`;
    return horaSeleccionada.value === tiempo;
  };
  
  // Actualiza la hora seleccionada y emite el evento
  const seleccionarHora = (hora, minuto) => {
    const tiempoSeleccionado = `${hora}:${minuto}:00`;
    if (!esHoraOcupada(hora, minuto)) {
      horaSeleccionada.value = tiempoSeleccionado; // Marca la hora seleccionada
      emit('horaSeleccionada', tiempoSeleccionado);
    }
  };
  </script>
  
  <style scoped>
  /* Tailwind ya proporciona estilos, pero puedes agregar estilos personalizados si lo deseas */
  </style>
  
  