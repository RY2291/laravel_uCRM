<script setup>
import { getToday } from '@/common';
import { onMounted, reactive, ref,computed } from 'vue';
import  { Inertia } from '@inertiajs/inertia';

const quantity = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10'];
const itemList = ref([]);

const props = defineProps({
  customers: Array,
  items: Array
})

const form = reactive({
    date: null,
    customer_id: null,
    status: true,
    items: []
})

const totalPrice = computed( () =>{
    let total = 0;
    itemList.value.forEach( item =>{
        total += item.price * item.quantity
    })
    return total
})

const storePurchase = () =>{
    itemList.value.forEach( item => {
        if( item.quantity > 0){
            form.items.push({
                id: item.id,
                quantity: item.quantity
            })
        }
    })
    Inertia.post(route('purchase.store'), form);
}

onMounted(() => {
    form.date = getToday();
    props.items.forEach( item => {
        itemList.value.push({
            id: item.id,
            name: item.name,
            price: item.price,
            quantity: 0
        })
    })
})

</script>
<template>
    <form @submit.prevent="storePurchase">
        <table>
            <thead>
                <tr>
                    <td>ID</td>
                    <td>商品名</td>
                    <td>金額</td>
                    <td>数量</td>
                    <td>小計</td>
                </tr>
            </thead>
            <tbody>
                <tr v-for="item in itemList">
                    <td>{{ item.id }}</td>
                    <td>{{ item.name }}</td>
                    <td>{{ item.price }}</td>
                    <td>
                        <select name="quantity" v-model="item.quantity">
                            <option :value="q" v-for="q in quantity">
                                {{ q }}
                            </option>
                        </select>
                    </td>
                    <td>
                        {{ item.quantity * item.price }}
                    </td>
                </tr>
            </tbody>
        </table>
        <br>
        合計: {{ totalPrice }}
        <br>
        日付
        <input type="date" name="date" v-model="form.date">
        <div>
            <select name="customer" v-model="form.customer_id">
                <option value=""></option>
                <option v-for="customer in customers" :value="customer.id" :key="customer.id">
                    {{ customer.id }} : {{ customer.name }}
                </option>
            </select>
        </div>
        <button >購入する</button>
    </form>
</template>