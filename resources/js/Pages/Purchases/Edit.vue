<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { onMounted, reactive, ref, computed } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import dayjs from 'dayjs';

const quantity = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10'];
const itemList = ref([]);

const props = defineProps({
  items: Array,
  order: Array,
  errors: Object
})

const form = reactive({
  date: dayjs(props.order[0]['created_at']).format('YYYY-MM-DD'),
  customer_id: props.order[0]['customer_id'],
  status: props.order[0]['status'],
  items: [],
  purchase_id: props.order[0]['id']
})

const totalPrice = computed(() => {
  let total = 0;
  itemList.value.forEach(item => {
    total += item.price * item.quantity
  })
  return total
})

const updatePurchase = () => {
  itemList.value.forEach(item => {
    if (item.quantity > 0) {
      form.items.push({
        id: item.id,
        quantity: item.quantity
      })
    }
  })
  Inertia.put(route('purchase.update', { purchase: form.purchase_id}), form);
}

onMounted(() => {
  console.log(props.items)
  props.items.forEach(item => {
    itemList.value.push({
      id: item.id,
      name: item.name,
      price: item.price,
      quantity: item.quantity
    })
  })
})


</script>

<template>

  <Head title="購入履歴編集画面" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        購入履歴編集
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            <!-- バリデーションコンポーネント -->
            <BreezeValidationErrors :errors="props.errors" />
            <!-- tailblock -->
            <section class="text-gray-600 body-font relative">
              <form @submit.prevent="updatePurchase">
                <div class="container px-5 py-24 mx-auto">
                  <div class="lg:w-1/2 md:w-2/3 mx-auto">
                    <div class="flex flex-wrap -m-2">
                      <div class="p-2 w-full">
                        <div class="relative">
                          <label for="date" class="leading-7 text-sm text-gray-600">日付</label>
                          <input type="date" id="date" name="date" :value="form.date" customer
                            class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                        </div>
                      </div>
                      <div class="p-2 w-full">
                        <div class="relative">
                          <label for="customer" class="leading-7 text-sm text-gray-600">会員名</label>
                          <input type="text" id="customer" name="customer" :value="props.order[0]['customer_name']" disabled
                            class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                        </div>
                      </div>
                      <table class="table-auto w-full text-left whitespace-no-wrap">
                        <thead>
                          <tr>
                            <th
                              class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">
                              ID</th>
                            <th
                              class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">
                              商品名</th>
                            <th
                              class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">
                              金額</th>
                            <th
                              class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">
                              数量</th>
                            <th
                              class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">
                              小計</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="item in itemList" :key="item.id">
                            <td class="px-4 py-3 border-b-2 border-gray-200">
                              <Link :href="route('items.show', { item: item.id })">{{ item.id }}</Link>
                            </td>
                            <td class="px-4 py-3 border-b-2 border-gray-200">{{ item.name }}</td>
                            <td class="px-4 py-3 border-b-2 border-gray-200">{{ item.price }}</td>
                            <td class="px-4 py-3 border-b-2 border-gray-200">
                              <select name="quantity" v-model="item.quantity">
                                <option :value="q" v-for="q in quantity">
                                  {{ q }}
                                </option>
                              </select>
                            </td>
                            <td class="px-4 py-3 border-b-2 border-gray-200">{{ item.quantity * item.price }}</td>
                          </tr>
                        </tbody>
                      </table>
                      <div class="p-2 w-full">
                        <div class="relative">
                          <label for="status" class="leading-7 text-sm text-gray-600 mr-4">ステータス</label>
                          <input type="radio" id="status" v-model="form.status" name="status" value="1" class="ml-4">未キャンセル
                          <input type="radio" id="status" v-model="form.status" name="status" value="0" class="ml-4">キャンセルする
                        </div>
                      </div>
                      <div class="p-2 w-full">
                        <div>
                          <label for="price" class="leading-7 text-sm text-gray-600">合計金額</label>
                          <div class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            {{ totalPrice }} 円
                          </div>
                        </div>
                      </div>
                      <div class="p-2 w-full">
                        <button
                          class="flex mx-auto text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">登録</button>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
            </section>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
