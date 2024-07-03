<script setup>
import { ref } from 'vue';
const props = defineProps({
  actual: Object
});

</script>
<template>
                    <table class="table-fixed">
                <thead>
                    <tr class="bg-slate-800 text-white h-12">
                        <th  class="border">Fechas de Ventas Anteriores</th>
                        <th  class="border">Nombre Cliente</th>
                        <th  class="border">Datos Factura</th>
                        <th  class="border">Dirección</th>
                        <th  class="border">Telefonos</th>
                        <th  class="border">Celulares</th>
                        <th  class="border">Observaciones</th>
                        <th  class="border">Campo Libre</th>
                        <th  class="border">Correo</th>
                        <th  class="border">Estado</th>
                        <th  class="border">Info Cisterna</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="bg-slate-800">
                        <td class="text-white border"></td>
                        <td class="text-white border">{{ props.actual.nombre_cliente }}</td>
                        <td class="text-white border">{{ props.actual.datos_factura }}</td>
                        <td class="text-white border text-sm w-[25%]">{{ props.actual.direccion }}</td>
                        <td class="text-white border">
                            <div class="flex flex-col">
                                <div>{{ props.actual.telefono }}</div>
                                <div>{{ props.actual.telefono_oficina }}</div>
                            </div>
                        </td>
                        <td class="text-white border">
                            <div class="flex flex-col">
                                <div>{{ props.actual.celular1 }}</div>
                                <div>{{ props.actual.celular2 }}</div>
                            </div>
                        </td>
                        <td class="text-white border">{{ props.actual.observaciones }}</td>
                        <td class="text-white border"></td>
                        <td class="text-white border">{{ props.actual.correo }}</td>
                        <td class="text-white border">{{ props.actual.estado }}</td>
                        <td class="text-white border">{{ props.actual.informacion_cisterna }}</td>
                    </tr>
                </tbody>
                </table>
</template>