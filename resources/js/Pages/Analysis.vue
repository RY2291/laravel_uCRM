<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { onMounted, reactive } from 'vue';
import { getToday } from '@/common';
import axios from 'axios';
import Chart from '@/Components/Chart.vue';

const form = reactive({
  startDate: null,
  endDate: null,
  type: 'perDay'
});

const data = reactive({});

onMounted(() => {
  // form.startDate = '2022-01-01';
  form.startDate = getToday();
  form.endDate = getToday();
});

const getDate = async () => {
  try {
    await axios.get('api/analysis', {
      params: {
        startDate: form.startDate,
        endDate: form.endDate,
        type: form.type
      }
    })
      .then(res => {
        // console.log(res.data)
        data.data = res.data.data
        data.labels = res.data.labels
        data.totals = res.data.totals
      })
  } catch (e) {
    console.log(e.message)
  }
}

</script>

<template>

  <Head title="データ分析" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        データ分析
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <form @submit.prevent="getDate">
            <input type="date" v-model="form.startDate" name="startDate">
            <input type="date" v-model="form.endDate" name="endDate">
            <button
              class="flex mx-auto text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">分析する</button>
          </form>
          <div v-show="data.data">
            <Chart :data="data" />
          </div>

          <div v-show="data.data" class="lg:w-2/3 w-full mx-auto overflow-auto">
            <table class="table-auto w-full text-left whitespace-no-wrap">
              <thead>
                <tr>
                  <th
                    class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">
                    年月</th>
                  <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">
                    金額</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="data in data.data" :key="data.date">
                  <td class="px-4 py-3 border-b-2 border-gray-200">{{ data.date }}</td>
                  <td class="px-4 py-3 border-b-2 border-gray-200">{{ data.total }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
