<script setup>
import { ref } from 'vue';
const props = defineProps({
  items: Object
});
</script>
<template>
                    <div class="flex justify-center mt-10 mb-10">
                    <span class="bg-slate-500 p-2 text-white rounded-md font-semibold">Comentarios historicos</span>
                </div>
                <div class="flex flex-col">
                <table class="table-fixed w-[66%] mx-auto">
                <thead>
                    <tr class="bg-slate-800 text-white h-12">
                        <th  class="border">Fechas programada</th>
                        <th  class="border">Tipo</th>
                        <th  class="border">Comentario</th>
                        <th  class="border">Fecha de gestión</th>
                        <th  class="border">Operador</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in props.items">
                        
                        <td v-if="index % 2 === 0" class="text-white bg-slate-600 border">{{ item.fecha }}</td>
                        <td v-else class="text-white bg-slate-400 border">{{ item.fecha }}</td>

                        <td v-if="index % 2 === 0" class="text-white bg-slate-600 border">{{ item.estado }}</td>
                        <td v-else class="text-white bg-slate-400 border">{{ item.estado }}</td>

                        <td v-if="index % 2 === 0" class="text-white bg-slate-600 border">{{ item.comentario }}</td>
                        <td v-else class="text-white bg-slate-400 border">{{ item.comentario }}</td>

                        <td v-if="index % 2 === 0" class="text-white bg-slate-600 border">{{ item.fecha_gestion }}</td>
                        <td v-else class="text-white bg-slate-400 border">{{ item.fecha_gestion }}</td>

                        <td v-if="index % 2 === 0" class="text-white bg-slate-600 border">{{ item.id_operador }}</td>
                        <td v-else class="text-white bg-slate-400 border">{{ item.id_operador }}</td>

                        
                    </tr>
                </tbody>
                </table>
                </div>
</template>