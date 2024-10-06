<script setup>
import { Chart, registerables } from 'chart.js';
import { BarChart } from 'vue-chart-3';
import { computed, onMounted, reactive } from 'vue';
import { data } from 'autoprefixer';

Chart.register(...registerables);

const props = defineProps({
    "data": Object
})

onMounted(()=> console.log(props.data));

const labels = computed(() => props.data.labels)
const totals = computed(() => props.data.totals)

const barData = reactive({
    labels: labels,
    datasets: [
        {
            label: '売上',
            data: totals,
            backgroundColor: "rgb(75, 192, 192)",
            tension: 2,
        }
    ]
})
</script>

<template>
    <div v-show="props.data">
        <BarChart :chartData="barData" />
    </div>
</template>