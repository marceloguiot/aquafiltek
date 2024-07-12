<script setup>
import { ref, onBeforeMount } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from '@headlessui/vue';
import ModalGestion from '@/Components/ModalGestion.vue';
import ClienteInfo from '@/Components/ClienteInfo.vue';
import ComentariosHistorico from '@/Components/ComentariosHistorico.vue';

const show_gestion = ref(false);

const categories = ref({
  'LlAMADAS URGENTES': [
    {
    },
  ],
  'LlAMADAS ESTA SEMANA': [
    {
    },
  ],
})


const comentarios = ref([]);
const gestionesPast = ref([]);


      const fetchComentarios = async (id) => {
        const data = { id: id };
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

const fetchPasadas = async (id) => {
  const data = { id: id };
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

const getLlamadas = async () =>{
  const response = await axios.post(route('getLlamadas'), {
      headers: {
        'Content-Type': 'application/json'
      }
    });
    llamsemana.value = response.data;
    console.log(llamsemana.value);
}


const actual = ref({});
const items = {};
const llamurgentes = ref([]);
const llamsemana = ref([]);


const mostrar_gestion = async (id, id_llamada) =>{

  fetchComentarios(id);
  fetchPasadas(id);

  const data = { id: id };
  const response = await axios.post(route('getDataClient'), data, {
      headers: {
        'Content-Type': 'application/json'
      }
    });
    
    actual.value = response.data;
    actual.value.id_llamada = id_llamada;
    console.log(actual.value);

  show_gestion.value = true;
}

const ocultar_gestion = () =>{
  show_gestion.value = false;
}

onBeforeMount(getLlamadas);
</script>

<template>
    <Head title="Recordatorios" />

    <AuthenticatedLayout>
        <div class="py-12">
            <TabGroup>
      <TabList class="flex space-x-1 rounded-xl bg-blue-900/20 p-1">
        <Tab
          v-for="category in Object.keys(categories)"
          as="template"
          :key="category"
          v-slot="{ selected }"
        >
          <button v-if="category == 'LlAMADAS URGENTES'"
            :class="[
              'w-full rounded-lg py-2.5 text-sm font-medium leading-5',
              'ring-white/60 ring-offset-2 ring-offset-blue-400',
              selected
                ? 'bg-red-600 text-white shadow'
                : 'text-white bg-red-300 hover:bg-red-200 hover:text-white',
            ]"
           @click="ocultar_gestion">{{ category }}</button>
          <button v-else
            :class="[
              'w-full rounded-lg py-2.5 text-sm font-medium leading-5',
              'ring-white/60 ring-offset-2 ring-offset-blue-400',
              selected
                ? 'bg-amber-400 text-white shadow'
                : 'text-white bg-orange-200 hover:bg-orange-300 hover:text-white',
            ]"
            @click="ocultar_gestion">{{ category }}</button>
            
          
        </Tab>
      </TabList>

      <TabPanels class="mt-2">
        <TabPanel
          v-for="(posts, idx) in Object.keys(categories)"
          :key="idx"
          :class="[
            'rounded-xl bg-white p-3',
            'ring-white/60 ring-offset-2 ring-offset-blue-400',
          ]"
        >
          <div v-if="idx == 0" class="flex flex-col w-[96%] mx-auto bg-red-200">
  <table class="table-auto">
  <thead>
    <tr class="h-12 bg-red-400">
      <th class="border border-slate-400">ID Cliente</th>
      <th class="border border-slate-400">Nombre cliente</th>
      <th class="border border-slate-400">Fecha de llamada</th>
      <th class="border border-slate-400">Hora de llamada</th>
    </tr>
  </thead>
  <tbody>
    <tr v-for="post in llamsemana.llamadasVencidas"
    :key="post.id"  class="hover:cursor-pointer" @click="mostrar_gestion(post.codigo, post.id)">
      <td class="border border-slate-400 h-10">{{ post.codigo }}</td>
      <td class="border border-slate-400 h-10">{{ post.nombre_cliente }}</td>
      <td class="border border-slate-400 h-10">{{ post.fecha_llamada }}</td>
      <td class="border border-slate-400 h-10">{{ post.hora_llamada }}</td>
    </tr>
  </tbody>
</table>

          </div>
          <div v-else class="flex flex-col w-[96%] mx-auto bg-yellow-200">
  <table class="table-auto">
  <thead>
    <tr class="h-12 bg-amber-400">
      <th class="border border-slate-400">ID Cliente</th>
      <th class="border border-slate-400">Nombre cliente</th>
      <th class="border border-slate-400">Fecha de llamada</th>
      <th class="border border-slate-400">Hora de llamada</th>
    </tr>
  </thead>
  <tbody>
    <tr v-for="post in llamsemana.llamadas"
    :key="post.id" class="hover:cursor-pointer"  @click="mostrar_gestion(post.codigo, post.id)">
      <td class="border border-slate-400 h-10">{{ post.codigo }}</td>
      <td class="border border-slate-400 h-10">{{ post.nombre_cliente }}</td>
      <td class="border border-slate-400 h-10">{{ post.fecha_llamada }}</td>
      <td class="border border-slate-400 h-10">{{ post.hora_llamada }}</td>
    </tr>
  </tbody>
</table>

          </div>
        </TabPanel>
      </TabPanels>
    </TabGroup>

                <div class="flex flex-col w-5/6 mx-auto" v-show="show_gestion">
                <ModalGestion  :actual="actual" :scope="'llamadas'" :key="actual" />
                <div class="flex flex-col">
                <ClienteInfo :actual="actual" :key="actual" :ventas="gestionesPast" />
                </div>
                <ComentariosHistorico :items="comentarios" />
                </div>
    
        </div>
    </AuthenticatedLayout>


    
</template>
