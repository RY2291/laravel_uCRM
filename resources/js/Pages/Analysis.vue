<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { onMounted, reactive } from 'vue';
import { getToday } from '@/common';
import axios from 'axios';
import Chart from '@/Components/Chart.vue';
import ResultTable from '@/Components/ResultTable.vue';

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
        console.log(res.data)
        data.data = res.data.data
        data.labels = res.data.labels
        data.totals = res.data.totals
        data.type = res.data.type
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
            <div>分析方法</div>
            <div class="mb-3">
              <input type="radio" name="perDay" value="perDay" v-model="form.type" checked><span class="mr-2">日別</span>
              <input type="radio" name="perMonth" value="perMonth" v-model="form.type"><span class="mr-2">月別</span>
              <input type="radio" name="perYear" value="perYear" v-model="form.type"><span class="mr-2">年別</span>
              <input type="radio" name="decile" value="decile" v-model="form.type"><span class="mr-2">デシル分析</span>
            </div>
            FROM:<input type="date" v-model="form.startDate" name="startDate">
            TO:<input type="date" v-model="form.endDate" name="endDate">
            <button
              class="flex mx-auto text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">分析する</button>
          </form>
          <div v-show="data.data">
            <Chart :data="data" />
            <ResultTable :data="data" />
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
