<template>
          <Head title="Reportes" />

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
          <div class="border p-1 h-24" v-for="day in daysInMonth" :key="day">
            <div class="text-xs text-end">{{ day }}</div>
            <div v-for="gestion in getGestionesForDay(day)" :key="gestion.id">
              <div>{{ gestion.hora }}</div>
              <div>{{ gestion.descripcion }}</div>
            </div>
          </div>
        </div>
      </div>
  
      <div v-if="activeTab === 'week'">
        <div class="grid grid-cols-7 gap-4 bg-white py-10 px-3 rounded-md">
          <div class="font-bold" v-for="day in weekDays" :key="day">{{ day }}</div>
          <div class="border p-2" v-for="day in daysInWeek" :key="day">
            <div class="text-xs text-end h-24">{{ day }}</div>
            <div v-for="gestion in getGestionesForDay(day)" :key="gestion.id">
              <div>{{ gestion.hora }}</div>
              <div>{{ gestion.descripcion }}</div>
            </div>
          </div>
        </div>
      </div>
  
      <div v-if="activeTab === 'day'">
        <div class="flex flex-col bg-white px-3 py-10 rounded-md">
          <div class="border p-2" v-for="hour in hoursInDay" :key="hour">{{ hour }}</div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
  </template>
  <script setup>
  import { ref, computed, onMounted } from 'vue';
  import axios from 'axios';
  import { format, addMonths, subMonths, addWeeks, subWeeks, addDays, subDays, startOfMonth, endOfMonth, eachDayOfInterval, startOfWeek, endOfWeek, eachHourOfInterval, getDay } from 'date-fns';
  import { es } from 'date-fns/locale';
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  import { Head } from '@inertiajs/vue3';
  
  // Tabs state
  const activeTab = ref('month');
  const currentDate = ref(new Date());
  const gestiones = ref([]);
  
  // Helper functions to generate days and hours
  const formatDate = (date, formatStr) => format(date, formatStr, { locale: es });
  
  // Fetch gestiones data from API
  const fetchGestiones = async () => {
    try {
      const response = await axios.get('/api/getGestiones');
      gestiones.value = response.data;
    } catch (error) {
      console.error(error);
    }
  };
  
  // Get gestiones for a specific day
  const getGestionesForDay = (day) => {
    const formattedDay = `${currentYear.value}-${currentMonthNumber.value}-${day < 10 ? '0' : ''}${day}`;
    return gestiones.value.filter((gestion) => gestion.fecha === formattedDay || gestion.fecha_acepto === formattedDay);
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
    } else if (activeTab.value === 'week') {
      currentDate.value = subWeeks(currentDate.value, 1);
    } else if (activeTab.value === 'day') {
      currentDate.value = subDays(currentDate.value, 1);
    }
  };
  
  const next = () => {
    if (activeTab.value === 'month') {
      currentDate.value = addMonths(currentDate.value, 1);
    } else if (activeTab.value === 'week') {
      currentDate.value = addWeeks(currentDate.value, 1);
    } else if (activeTab.value === 'day') {
      currentDate.value = addDays(currentDate.value, 1);
    }
  };
  
  // Fetch gestiones on mounted
  onMounted(fetchGestiones);
  </script>
  
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
  
  
  