<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { onMounted, reactive, ref } from 'vue';
import { getToday } from '@/common';
import axios from 'axios';
import Chart from '@/Components/Chart.vue';
import ResultTable from '@/Components/ResultTable.vue';

const form = reactive({
  startDate: null,
  endDate: null,
  type: 'perDay',
  rfmPrms: [14, 28, 60, 90, 7, 5, 3, 2, 300000, 200000, 100000, 30000]
});

const data = reactive({});
const showRfmInput = ref(false); // 表示/非表示を管理する変数

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
        type: form.type,
        rfmPrms: form.rfmPrms
      }
    })
      .then(res => {
        console.log(res.data)
        data.data = res.data.data
        if(res.data.labels) {data.labels = res.data.labels}
        if(res.data.eachCount) {data.eachCount = res.data.eachCount}
        data.totals = res.data.totals
        data.type = res.data.type
      })
  } catch (e) {
    console.log(e.message)
  }
}

const toggleRfmInput = () => {
  showRfmInput.value = !showRfmInput.value; // 状態を切り替える
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
              <input type="radio" name="rfm" value="rfm" v-model="form.type"><span class="mr-2">RFM分析</span>
            </div>
            FROM:<input type="date" v-model="form.startDate" name="startDate">
            TO:<input type="date" v-model="form.endDate" name="endDate">

            <!-- 表示切り替えボタンを追加 -->
            <button type="button" @click="toggleRfmInput" v-if="form.type === 'rfm'" class="my-4 bg-blue-500 text-white px-4 py-2 rounded">
              {{ showRfmInput ? 'RFM入力を非表示' : 'RFM入力を表示' }}
            </button>
            <div v-if="showRfmInput && form.type === 'rfm'" class="my-8" data-name="rfm-input">
              <table class="mx-auto">
                <thead>
                  <tr>
                    <th>ランク</th>
                    <th>R (○日以内)</th>
                    <th>F (○回以上)</th>
                    <th>M (○円以上)</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>5</td>
                    <td><input type="number" v-model="form.rfmPrms[0]"></td>
                    <td><input type="number" v-model="form.rfmPrms[4]"></td>
                    <td><input type="number" v-model="form.rfmPrms[8]"></td>
                  </tr>
                  <tr>
                    <td>4</td>
                    <td><input type="number" v-model="form.rfmPrms[1]"></td>
                    <td><input type="number" v-model="form.rfmPrms[5]"></td>
                    <td><input type="number" v-model="form.rfmPrms[9]"></td>
                  </tr>
                  <tr>
                    <td>3</td>
                    <td><input type="number" v-model="form.rfmPrms[2]"></td>
                    <td><input type="number" v-model="form.rfmPrms[6]"></td>
                    <td><input type="number" v-model="form.rfmPrms[10]"></td>
                  </tr>
                  <tr>
                    <td>2</td>
                    <td><input type="number" v-model="form.rfmPrms[3]"></td>
                    <td><input type="number" v-model="form.rfmPrms[7]"></td>
                    <td><input type="number" v-model="form.rfmPrms[11]"></td>
                  </tr>
                </tbody>
              </table>
            </div>
            <button
              class="flex mx-auto text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">分析する</button>
          </form>
          <div v-show="data.data">
            <div v-if="form.type != 'rfm'">
              <Chart :data="data" />
            </div>
            <ResultTable :data="data" />
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
