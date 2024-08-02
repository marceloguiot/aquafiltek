<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import DataTable from 'datatables.net-vue3';
import Select from 'datatables.net-select';

DataTable.use(Select);

const users = ref([]);

const fetchUsers = async () => {
  try {
    const response = await axios.get('/users');
    users.value = response.data;
  } catch (error) {
    console.error('Error fetching users:', error);
  }
};

const columns = [
  { title: 'Id', data: 'id' },
  { title: 'Nombre', data: 'name' },
  { title: 'Email', data: 'email' }
];

const options = {
    language:{
        url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-MX.json'
    }
};

onMounted(fetchUsers);
</script>
<template>
    <div>
      <h2 class="text-xl font-bold mb-4">Control de Usuarios</h2>
      <DataTable :data="users" :columns="columns" :options="options" class="display">
      </DataTable>
    </div>
  </template>
  <style>
   @import 'datatables.net-dt';
  </style>
  