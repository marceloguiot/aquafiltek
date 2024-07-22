<script setup>
import { ref, onBeforeMount, onMounted } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

import Modals from '@/Components/Modals.vue';
import ModalGestion from '@/Components/ModalGestion.vue';
import ClienteInfo from '@/Components/ClienteInfo.vue';
import ComentariosHistorico from '@/Components/ComentariosHistorico.vue';
import { usePage } from '@inertiajs/vue3';

import {
  TransitionRoot,
  TransitionChild,
  Dialog,
  DialogPanel,
  DialogTitle,
} from '@headlessui/vue';

const { props } = usePage();
const isOpenLlamada = ref(false);

const datos = ref(props.datos);

const actual = ref(datos.value[3]);
const comentarios = ref([]);
const gestionesPast = ref([]);
const upcomingLlamadas = ref([]);

const fetchUpcomingLlamadas = async () => {
  try {
    const response = await axios.get('/llamadas/upcoming');
    upcomingLlamadas.value = response.data;
    console.log(response.data);
    if(response.data.length > 0)
    {
      isOpenLlamada.value = true;
    }
  } catch (error) {
    console.error('Error fetching upcoming llamadas:', error);
  }
};

const handleUpdateEjecutado = (ejecutado) => {
  if(ejecutado == 'true')
{
  window.location.reload();
}
};

// Fetch upcoming llamadas every minute
onMounted(() => {
  fetchUpcomingLlamadas(); // Fetch on mount
  setInterval(fetchUpcomingLlamadas, 60000); // Fetch every minute
});


      const fetchComentarios = async () => {
        const data = { id: actual.value.codigo };
  try {
    const response = await axios.post(route('getComentarios'), data, {
      headers: {
        'Content-Type': 'application/json'
      }
    });
    comentarios.value = response.data;
  } catch (err) {
    console.error(err);
    
  } finally {
    
  }
};

const fetchPasadas = async () => {
  const data = { id: actual.value.codigo };
  try {
    const response = await axios.post(route('getPasadas'), data, {
      headers: {
        'Content-Type': 'application/json'
      }
    });
    gestionesPast.value = response.data;
  } catch (err) {
    console.error(err);
    
  }
}

function closeModal() {
  isOpenLlamada.value = false;
}

      onBeforeMount(fetchComentarios);
      onBeforeMount(fetchPasadas);

</script>

<template>
    <Head title="Tablero" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-[94%] mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">Bienvenido</div>
                </div>
                    <!-- Inician modales-->
                    <Modals :actual="actual" />
                    <!-- Terminan modales-->
                <div class="flex flex-row justify-center mt-5 h-40">

                    <div v-for="(dat, index) in datos" class="h-42 w-[14%] flex flex-col">
                        <div v-if="index == 3" class="h-16 text-xs bg-teal-500 overflow-auto text-center content-center border">{{ dat.direccion }}</div>
                        <div v-else-if="index < 3" class="h-16 text-xs bg-yellow-400 overflow-auto text-center content-center border">{{ dat.direccion }}</div>
                        <div v-else class="h-16 text-xs bg-orange-400 overflow-auto text-center content-center border">{{ dat.direccion }}</div>
                        
                        <div v-if="index == 3" class="h-20 text-sm bg-teal-500 text-center content-center border">{{ dat.nombre_cliente}}</div>
                        <div v-else-if="index < 3" class="h-20 text-sm bg-yellow-400 text-center content-center border">{{ dat.nombre_cliente}}</div>
                        <div v-else class="h-20 text-sm bg-orange-400 text-center content-center border">{{ dat.nombre_cliente}}</div>
        
                        <div v-if="index == 3" class="h-12 text-sm bg-teal-500 text-center content-center border">{{ dat.estado }}</div>
                        <div v-else-if="index < 3" class="h-12 text-sm bg-yellow-400 text-center content-center border">{{ dat.estado }}</div>
                        <div v-else class="h-12 text-sm bg-orange-400 text-center content-center border">{{ dat.estado }}</div>
                
                    </div>
                </div>
                <ModalGestion  :actual="actual" :scope="'gestion'" @updateEjecutado="handleUpdateEjecutado" />
                <div class="flex flex-col">
                <ClienteInfo :actual="actual" :ventas="gestionesPast" />
                </div>
                <ComentariosHistorico :items="comentarios" />
            </div>
        </div>
    </AuthenticatedLayout>


    <TransitionRoot appear :show="isOpenLlamada" as="template">
    <Dialog as="div" @close="closeModal" class="relative z-10">
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
              class="w-full max-w-[80%] transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all"
            >
              <DialogTitle
                as="h3"
                class="text-2xl font-bold text-blue-500"
              >
                Llamadas proximas a realizarse
              </DialogTitle>
              <div class="mt-2">
              <div class="flex flex-col" v-for="item in upcomingLlamadas">
                <div class="mb-4 justify-center">
                  <table class="w-[98%] mt-10">
                    <thead>
                      <tr>
                        <th class="w-[33%] border border-slate-600 p-1 bg-slate-200">Nombre del cliente</th>
                        <th class="w-[25%] text-center border border-slate-600 p-1 bg-slate-200">Fecha de llamada</th>
                        <th class="w-[25%] text-center border border-slate-600 p-1 bg-slate-200">Hora de llamada</th>
                        <th class="w-[17%] text-center border border-slate-600 p-1 bg-slate-200">Opción</th>
                      </tr>
                    </thead>
                    <tbody>
                      <td class="border border-slate-300">{{ item.codigo }}</td>
                      <td class="border border-slate-300 text-center">{{ item.fecha_llamada }}</td>
                      <td class="border border-slate-300 text-center">{{ item.hora_llamada }}</td>
                      <td class="border border-slate-300 text-center py-2"> <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">ir a llamada</button>
                      </td>


                    </tbody>
                  </table>
                  <div class="flex mt-10">
                  
                  <a :href="route('recordatorios')" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                    Ir a recordatorios
                                </a>
                  </div>
                   </div>
              </div>
              </div>
              <div class="flex justify-center mt-16">
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
    
</template>
