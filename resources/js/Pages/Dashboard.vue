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
import axios from 'axios';

const { props } = usePage();
const isOpenLlamada = ref(false);
const isOpenInac = ref(false);

const datos = ref(props.datos.previas.original);

const datos_prox = ref(props.datos.proximas.original);


const actual = ref(props.datos.actual);



const comentarios = ref([]);
const gestionesPast = ref([]);
const upcomingLlamadas = ref([]);
const permiso_editar = ref(0);
const permiso_inactivar = ref(0);
const motivo = ref("");
const handleClienteSeleccionado = async (cliente) => {
  actual.value = await cliente;
  fetchComentarios();
};
const fetchUpcomingLlamadas = async () => {
  try {
    const response = await axios.get('/llamadas/upcoming');
    upcomingLlamadas.value = response.data;
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

const getClients = async () => {
  const response = await axios.get('/proximas_gestiones');
  

  const responseprev = await axios.get('/previas_gestiones');

  
}

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

const fetchPermisos = async () => {
  try {
        const response = await axios.get('/permisos');
        console.log(response.data.editar);
        permiso_editar.value = response.data.editar;
        permiso_inactivar.value = response.data.inactivar;
    } catch (error) {
        console.error("Error fetching permisos:", error);
        return null;
    }
}

function closeModal() {
  isOpenLlamada.value = false;
}

function closeModalInac() {
  isOpenInac.value = false;
}


const inactivar = () => {
  isOpenInac.value = true;
}


const registrar_inactivo = async (id_cliente) => {
  try {
        const response = await axios.post('/cliente/inactivar', {
            id_cliente: id_cliente,
            motivo: motivo.value,
        });
        if (response.data.success) {
            console.log('Inactivation registered successfully:', response.data.data);
        } else {
            console.error('Failed to register inactivation:', response.data.message);
        }
    } catch (error) {
        console.error('Error during inactivation registration:', error);
    }
}
      onBeforeMount(fetchComentarios);
      onBeforeMount(fetchPasadas);
      onBeforeMount(fetchPermisos);
      onBeforeMount(getClients);

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
                    <Modals :actual="actual" :key="actual.codigo" @clienteSeleccionado="handleClienteSeleccionado" />
                    <!-- Terminan modales-->
                <div class="flex flex-row justify-center mt-5">                
                  <div class="flex flex-row">
                    <div v-for="dat in datos">
                      <div class="h-16 text-xs bg-yellow-400 overflow-auto text-center content-center border hover:cursor-pointer" @click="handleClienteSeleccionado(dat)">{{ dat.direccion }}</div>
                      <div class="h-20 text-sm bg-yellow-400 text-center content-center border p-1 hover:cursor-pointer" @click="handleClienteSeleccionado(dat)">{{ dat.nombre_cliente}}</div>
                      <div class="h-12 text-sm bg-yellow-400 text-center content-center border hover:cursor-pointer" @click="handleClienteSeleccionado(dat)">{{ dat.tipo }}</div>
                    </div>
                    <div>
                      <div class="h-16 text-xs bg-teal-400 overflow-auto text-center content-center border">{{ actual.direccion }}</div>
                      <div class="h-20 text-sm bg-teal-400 text-center content-center border p-1">{{ actual.nombre_cliente}}</div>
                      <div class="h-12 text-sm bg-teal-400 text-center content-center border">{{ actual.tipo }}</div>
                    </div>
                    
                    <div v-for="dat in datos_prox">
                      <div class="h-16 text-xs bg-orange-400 overflow-auto text-center content-center border hover:cursor-pointer" @click="handleClienteSeleccionado(dat)">{{ dat.direccion }}</div>
                      <div class="h-20 text-sm bg-orange-400 text-center content-center border p-1 hover:cursor-pointer" @click="handleClienteSeleccionado(dat)">{{ dat.nombre_cliente}}</div>
                      <div class="h-12 text-sm bg-orange-400 text-center content-center border hover:cursor-pointer" @click="handleClienteSeleccionado(dat)">{{ dat.tipo }}</div>
                    </div>
                  </div>
                </div>
                <ModalGestion  :actual="actual"  :key="actual.codigo" :scope="'gestion'" @updateEjecutado="handleUpdateEjecutado" />
                <div v-if="permiso_inactivar == 1">
                  <button class="bg-yellow-400 hover:bg-yellow-500 font-semibold p-2 rounded-md mb-4" @click="inactivar()">Inactivar cliente</button>
                </div>
                <div class="flex flex-col">
                <ClienteInfo :actual="actual" :ventas="gestionesPast" />
                </div>
                <ComentariosHistorico :actual="actual" :items="comentarios" />
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

  <TransitionRoot appear :show="isOpenInac" as="template">
    <Dialog as="div" @close="closeModalInac" class="relative z-10">
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
              class="w-[60%] transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all"
            >
              <DialogTitle
                as="h3"
                class="text-2xl font-bold text-blue-500"
              >
                Inactivar cliente
              </DialogTitle>
              <div class="mt-2">

              </div>
              <div class="flex flex-col mt-6 mb-10">
                <div class="flex flex-col">
                <label class="font-semibold">Cliente:</label>
                  <span>{{actual.nombre_cliente}}</span>
              </div>
                <label class="font-semibold mb-2 mt-5">Motivo de inactivación:</label>

                <textarea v-model="motivo"></textarea>
                <button class="mt-5 bg-sky-500 rounded-md p-2 text-white hover:bg-sky-400" @click="registrar_inactivo(actual.id)">Guardar</button>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
    
</template>
