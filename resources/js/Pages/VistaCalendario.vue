<script setup>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';
import { format, addMonths, subMonths, addWeeks, subWeeks, addDays, subDays, startOfMonth, endOfMonth, eachDayOfInterval, startOfWeek, endOfWeek, eachHourOfInterval, getDay } from 'date-fns';
import { es } from 'date-fns/locale';
import ModalGestionEdit from '@/Components/ModalGestionEdit.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import {
  TransitionRoot,
  TransitionChild,
  Dialog,
  DialogPanel,
  DialogTitle,
} from '@headlessui/vue';
import Swal from 'sweetalert2';
import { router } from '@inertiajs/vue3';


const open_modges = ref(false);
const actual_gest = ref([]);
const datos_edit = ref([]);
const refedit = ref(0);

// Tabs state
const activeTab = ref('month');
const currentDate = ref(new Date());
const gestiones = ref([]);
const view = ref('month');

// Helper functions to generate days and hours
const formatDate = (date, formatStr) => format(date, formatStr, { locale: es });

const fetchGestiones = async () => {
const dateRange = getDateRange();
try {
  const response = await axios.post('/api/getGestiones', dateRange);
  gestiones.value = response.data;
} catch (error) {
  console.error('Error fetching gestiones:', error);
}
};

const setView = (newView) => {
view.value = newView;
fetchGestiones();
};

const modal_cal = (gestion) =>{
  open_modges.value = true;
  actual_gest.value = gestion;
  }

const closeModges = () =>{
  open_modges.value = false;
}

const getDateRange = () => {
let start, end;
if (view.value === 'month') {
  start = startOfMonth(currentDate.value);
  end = endOfMonth(currentDate.value);
} else if (view.value === 'week') {
  start = startOfWeek(currentDate.value);
  end = endOfWeek(currentDate.value);
} else {
  start = startOfDay(currentDate.value);
  end = endOfDay(currentDate.value);
}
return { fecha_inicio: format(start, 'yyyy-MM-dd'), fecha_fin: format(end, 'yyyy-MM-dd') };
};

// Get gestiones for a specific day
const getGestionesForDay = (day, tipo) => {
  if(tipo === 'mes')
{
  const formattedDay = `${currentYear.value}-${currentMonthNumber.value}-${day < 10 ? '0' : ''}${day}`;
  return gestiones.value.filter((gestion) => gestion.fecha === formattedDay || gestion.fecha_acepto === formattedDay);
}
else
{
  const dateParts = day.split('-');
  const formattedDay = `${currentYear.value}-${currentMonthNumber.value}-${dateParts[0] < 10 ? '0' : ''}${dateParts[0]}`;
  return gestiones.value.filter((gestion) => gestion.fecha === formattedDay || gestion.fecha_acepto === formattedDay);
}

};

const getGestionesForHour = (day, hour) => {
  const dateParts = day.split(' ');
  const formattedDay = `${currentYear.value}-${currentMonthNumber.value}-${dateParts[0] < 10 ? '0' : ''}${dateParts[0]}`;
  return gestiones.value.filter((gestion) => gestion.fecha === formattedDay && gestion.hora.slice(0, 2) == parseInt(hour) || gestion.fecha_acepto === formattedDay && gestion.hora.slice(0, 2) == parseInt(hour));


};

// Calculate the offset to correctly position the first day of the month
const firstDayOffset = computed(() => {
  const firstDayOfMonth = startOfMonth(currentDate.value);
  return (getDay(firstDayOfMonth) + 6) % 7; // Adjusting so that Monday is the first day (0)
});

// Generate days of the month
const daysInMonth = computed(() => {
  const start = startOfMonth(currentDate.value);
  const end = endOfMonth(currentDate.value);
  const ver = eachDayOfInterval({ start, end }).map(date => formatDate(date, 'd'));
  return eachDayOfInterval({ start, end }).map(date => formatDate(date, 'd'));
});

// Generate days of the week
const daysInWeek = computed(() => {
  const weekStart = startOfWeek(currentDate.value, { weekStartsOn: 1 }); // Start on Monday
  const weekEnd = endOfWeek(currentDate.value, { weekStartsOn: 1 }); // End on Sunday
  return eachDayOfInterval({ start: weekStart, end: weekEnd }).map(date => formatDate(date, 'd-MM-yyyy'));
});

// Generate hours of the day
const hoursInDay = computed(() => {
  const hoursStart = currentDate.value.setHours(6, 0, 0, 0);
  const hoursEnd = currentDate.value.setHours(19, 0, 0, 0);
  return eachHourOfInterval({ start: hoursStart, end: hoursEnd }).map(hour => formatDate(hour, 'HH:mm'));
});

// Days of the week labels
const weekDays = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];

// Current month name
const currentMonth = computed(() => formatDate(currentDate.value, 'MMMM yyyy'));
const currentDay = computed(() => formatDate(currentDate.value, 'dd MMMM yyyy'));
const currentYear = computed(() => formatDate(currentDate.value, 'yyyy'));
const currentMonthNumber = computed(() => formatDate(currentDate.value, 'MM'));

// Navigation functions
const previous = () => {
  if (activeTab.value === 'month') {
    currentDate.value = subMonths(currentDate.value, 1);
    fetchGestiones();

  } else if (activeTab.value === 'week') {
    currentDate.value = subWeeks(currentDate.value, 1);
    fetchGestiones();

  } else if (activeTab.value === 'day') {
    currentDate.value = subDays(currentDate.value, 1);
    fetchGestiones();

  }
};

const next = () => {
  if (activeTab.value === 'month') {
    currentDate.value = addMonths(currentDate.value, 1);
    fetchGestiones();

  } else if (activeTab.value === 'week') {
    currentDate.value = addWeeks(currentDate.value, 1);
    fetchGestiones();

  } else if (activeTab.value === 'day') {
    currentDate.value = addDays(currentDate.value, 1);
    fetchGestiones();

  }
};

const eliminar_gest = async (gestion) =>{
  Swal.fire({
  title: "¿Está seguro de eliminar esta gestión?",
  text: "Esta acción no se puede revertir",
  icon: "warning",
  showCancelButton: true,
  confirmButtonColor: "#3085d6",
  cancelButtonColor: "#d33",
  cancelButtonText: "Cancelar",
  confirmButtonText: "Eliminar"
}).then(async (result) => {
  if (result.isConfirmed) {
    try {
    const response = await axios.post('/eliminar-gestion', {
      id: gestion.id,
      tipo: gestion.tipo
    });

    if (response.data.success) {
      Swal.fire({
      title: "¡Eliminada!",
      text: "Gestión eliminada correctamente.",
      confirmButtonText: "Aceptar",
      icon: "success"
    });
    open_modges.value = false;
    fetchGestiones();
    } else {
      //console.error('Error al eliminar la gestión:', response.data.message);
    }
  } catch (error) {
    console.error('Error al eliminar la gestión:', error);
  }


  }
});
}

const mover_gestion = (id) => {
  router.visit('/dashboard', { method: 'get', data: { id } });
}

const editar_gest = async (gestion) =>{
  try {
    const response = await axios.post('/editar-gestion', {
      id: gestion.id,
      tipo: gestion.tipo
    });

    if (response.data) {
      // Manejar la respuesta, por ejemplo, almacenar los detalles de la gestión
      datos_edit.value = response.data;
      refedit.value = refedit.value+1;
    }
  } catch (error) {
    console.error('Error al obtener los detalles de la gestión:', error);
  }
}

const handleUpdateEjecutado = () => {
  refedit.value = refedit.value+1;
  fetchGestiones();
}

onMounted(fetchGestiones);
</script>
<template>
          <Head title="Calendario" />

<AuthenticatedLayout>
    <div class="w-[88%] mx-auto p-4">
      <div class="flex flex-col">
      <span class="text-center mt-2 text-2xl font-semibold">Calendario de servicios</span>
    </div>
      <div class="tabs mb-8 mt-10">
        <button
          :class="{'tab-active': activeTab === 'month', 'tab': true}"
          @click="activeTab = 'month'"
        >
          Mes
        </button>
        <button
          :class="{'tab-active': activeTab === 'week', 'tab': true}"
          @click="activeTab = 'week'"
        >
          Semana
        </button>
        <button
          :class="{'tab-active': activeTab === 'day', 'tab': true}"
          @click="activeTab = 'day'"
        >
          Día
        </button>
      </div>
      <div class="flex flex-row justify-around mt-2 mb-2">
        <span class="bg-teal-500 w-full border border-slate-400 text-center">Aceptó</span>
        <span class="bg-blue-300 w-full border border-slate-400 text-center">Inspección</span>
        <span class="bg-yellow-500 w-full border border-slate-400 text-center">Cobros</span>
        <span class="bg-sky-600 w-full border border-slate-400 text-center">Importante</span>
        <span class="bg-red-500 w-full border border-slate-400 text-center">Competencia</span>
      </div>
  
      <div class="navigation mb-4 flex justify-between items-center">
        <button class="btn hover:bg-slate-400 hover:text-white hover:font-semibold" @click="previous">Anterior</button>
        <span class="text-xl font-bold" v-if="activeTab === 'month'">{{ currentMonth }}</span>
        <span class="text-xl font-bold" v-if="activeTab === 'day'">{{ currentDay }}</span>
        <button class="btn hover:bg-slate-400 hover:text-white hover:font-semibold" @click="next">Siguiente</button>
      </div>
  
      <div v-if="activeTab === 'month'">
        <div class="grid grid-cols-7 gap-4 bg-white p-3 rounded-md">
          <div class="font-bold" v-for="day in weekDays" :key="day">
            {{ day }}
          </div>
          <div v-for="n in firstDayOffset" :key="'empty-' + n"></div>
          <div class="border p-1 h-24 overflow-auto" v-for="day in daysInMonth" :key="day">
            <div class="text-xs text-end">{{ day }}</div>
            <div v-for="gestion in getGestionesForDay(day, 'mes')" :key="gestion.id">
              <span v-if="gestion.tipo == 'aceptada'" class="text-xs bg-teal-500 rounded-sm p-[0.5px] truncate hover:cursor-pointer" @click="modal_cal(gestion)">{{ gestion.hora }} {{ gestion.cliente }}</span>
              <span v-else-if="gestion.tipo == 'importante'" class="text-xs bg-sky-600 rounded-sm p-[0.5px] truncate hover:cursor-pointer" @click="modal_cal(gestion)">{{ gestion.hora }} {{ gestion.cliente }}</span>
              <span v-else-if="gestion.tipo == 'inspeccion'" class="text-xs bg-blue-300 rounded-sm p-[0.5px] truncate hover:cursor-pointer" @click="modal_cal(gestion)">{{ gestion.hora }} {{ gestion.cliente }}</span>
              <span v-else-if="gestion.tipo == 'competencia'" class="text-xs bg-red-400 rounded-sm p-[0.5px] truncate hover:cursor-pointer" @click="modal_cal(gestion)">{{ gestion.hora }} {{ gestion.cliente }}</span>
              <span v-else class="text-xs">{{ gestion.hora }} {{ gestion.cliente }}</span>
            </div>
          </div>
        </div>
      </div>
  
      <div v-if="activeTab === 'week'">
        <div class="grid grid-cols-7 gap-4 bg-white p-3 rounded-md">
          <div class="font-bold" v-for="day in weekDays" :key="day">{{ day }}</div>
          <div class="border p-1 overflow-auto h-40" v-for="day in daysInWeek" :key="day">
            <div class="text-xs text-end">{{ day }}</div>
            <div v-for="gestion in getGestionesForDay(day, 'semana')" :key="day">
              <span v-if="gestion.tipo == 'aceptada'" class="text-xs bg-teal-500 rounded-sm p-[0.5px] truncate hover:cursor-pointer" @click="modal_cal(gestion)">{{ gestion.hora }} {{ gestion.cliente }}</span>
              <span v-else-if="gestion.tipo == 'importante'" class="text-xs bg-sky-600 rounded-sm p-[0.5px] truncate hover:cursor-pointer" @click="modal_cal(gestion)">{{ gestion.hora }} {{ gestion.cliente }}</span>
              <span v-else-if="gestion.tipo == 'inspeccion'" class="text-xs bg-blue-300 rounded-sm p-[0.5px] truncate hover:cursor-pointer" @click="modal_cal(gestion)">{{ gestion.hora }} {{ gestion.cliente }}</span>
              <span v-else-if="gestion.tipo == 'competencia'" class="text-xs bg-red-400 rounded-sm p-[0.5px] truncate hover:cursor-pointer" @click="modal_cal(gestion)">{{ gestion.hora }} {{ gestion.cliente }}</span>

              <span v-else class="text-xs">{{ gestion.hora }} {{ gestion.cliente }}</span>
            </div>
          </div>
        </div>
      </div>
  
      <div v-if="activeTab === 'day'">
        <div class="flex flex-col bg-white px-3 py-10 rounded-md">
          <div class="border p-2 h-32 overflow-auto" v-for="hour in hoursInDay" :key="hour">{{ hour }}
            <div v-for="gestion in getGestionesForHour(currentDay, hour)" :key="day">
              <span v-if="gestion.tipo == 'aceptada'" class="text-sm bg-teal-500 rounded-sm p-[4.0px] hover:cursor-pointer" @click="modal_cal(gestion)">{{ gestion.hora }} {{ gestion.cliente }}</span>
              <span v-else-if="gestion.tipo == 'importante'" class="text-sm bg-sky-600 rounded-sm p-[4.0px] hover:cursor-pointer" @click="modal_cal(gestion)">{{ gestion.hora }} {{ gestion.cliente }}</span>
              <span v-else-if="gestion.tipo == 'inspeccion'" class="text-sm bg-blue-300 rounded-sm p-[4.0px] hover:cursor-pointer" @click="modal_cal(gestion)">{{ gestion.hora }} {{ gestion.cliente }}</span>
              <span v-else-if="gestion.tipo == 'competencia'" class="text-sm bg-red-400 rounded-sm p-[4.0px] hover:cursor-pointer" @click="modal_cal(gestion)">{{ gestion.hora }} {{ gestion.cliente }}</span>

              <span v-else class="text-xs">{{ gestion.hora }} {{ gestion.cliente }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
    <TransitionRoot appear :show="open_modges" as="template">
    <Dialog as="div" @close="closeModges" class="relative z-10">
      <TransitionChild
        as="template"
        enter="duration-300 ease-out"
        enter-from="opacity-0"
        enter-to="opacity-100"
        leave="duration-200 ease-in"
        leave-from="opacity-100"
        leave-to="opacity-0"
      >
        <div class="fixed inset-0 bg-black/25" />
      </TransitionChild>

      <div class="fixed inset-0 overflow-y-auto">
        <div
          class="flex min-h-full items-center justify-center p-4 text-center"
        >
          <TransitionChild
            as="template"
            enter="duration-300 ease-out"
            enter-from="opacity-0 scale-95"
            enter-to="opacity-100 scale-100"
            leave="duration-200 ease-in"
            leave-from="opacity-100 scale-100"
            leave-to="opacity-0 scale-95"
          >
            <DialogPanel
              class="w-full max-w-[50%] transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all"
            >
              <DialogTitle
                as="h3"
                class="text-2xl font-bold text-blue-500"
              >
                Detalles
              </DialogTitle>
              <div class="mt-2">
              <div class="flex flex-col">
              <div>
                <div class="flex flex-col">
                  <div class="flex-row justify-around mt-2">
                  <label class="font-semibold">Código:</label>
                  <span class="ml-2">{{ actual_gest.codigo }}</span>
                  </div>
                  <div class="flex-row justify-around mt-2">
                  <label class="font-semibold">Nombre:</label>
                  <span class="ml-2">{{ actual_gest.cliente }}</span>
                  </div>
                  <div class="flex-row justify-around mt-2">
                  <label class="font-semibold">Comentarios:</label>
                  <span class="ml-2">{{ actual_gest.descripcion }}</span>
                  </div>

                  <div class="flex-row justify-around mt-2">
                  <label class="font-semibold">Fecha del servicio:</label>
                  <span class="ml-2">{{ actual_gest.fecha }} {{ actual_gest.hora }}</span>
                  </div>
                  <div class="flex flex-row mt-5 justify-around">
                    <button class="bg-teal-500 w-20 h-8 rounded-md hover:bg-teal-400" @click="mover_gestion(actual_gest.codigo)">Ver</button>
                    <button class="bg-yellow-500 w-20 h-8 rounded-md hover:bg-yellow-400" @click="editar_gest(actual_gest)">Editar</button>
                    <button class="bg-red-500 w-20 h-8 rounded-md hover:bg-red-400" @click="eliminar_gest(actual_gest)">Eliminar</button>
                  </div>

                </div>
              </div>
              </div>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
  <ModalGestionEdit :actual="datos_edit"  :key="refedit" @updateEjecutado="handleUpdateEjecutado"/>
  </AuthenticatedLayout>
  </template>
<style scoped>
  .container {
    max-width: 800px;
  }
  
  .tabs {
    display: flex;
    justify-content: space-around;
  }
  
  .tab {
    padding: 0.5rem 1rem;
    border: 1px solid #ccc;
    border-radius: 4px;
    cursor: pointer;
  }
  
  .tab-active {
    background-color: #4A90E2;
    color: white;
  }
  
  .grid {
    grid-template-columns: repeat(7, 1fr);
  }
  
  .border {
    border: 1px solid #ccc;
    border-radius: 4px;
  }
  
  .p-2 {
    padding: 0.5rem;
  }
  
  .navigation .btn {
    padding: 0.5rem 1rem;
    border: 1px solid #ccc;
    border-radius: 4px;
    cursor: pointer;
  }
  </style>
  
  
  