<script setup>
import axios from 'axios';
import { ref, onMounted,reactive } from 'vue';

const search = ref("");
const customers = reactive({});

const isShow = ref(false);
const toggleStatus = () => {{ isShow.value = !isShow.value }}

const searchCustomers = async () => {
  try{
    await axios.get(`/api/searchCustomer/?search=${search.value}`)
    .then( res => {
      customers.value = res.data
    })
    isShow.value = !isShow.value
  } catch (e){
    console.log(e.message)
  }
}

// defineEmitsの引数に配列があるのは、複数のイベント名をもてるため。
const emit = defineEmits(['update:customerId']);

// @clickイベントが発火するとsetCustomerの引数がeとして受け取られる
const setCustomer = e => {
  search.value = e.kana
  emit('update:customerId', e.id)
  toggleStatus()
}
</script>

<template>
  <div class="modal" v-show="isShow" id="modal-1" aria-hidden="true">
    <div class="modal__overlay" tabindex="-1" data-micromodal-close>
      <div class="modal__container w-2/3" role="dialog" aria-modal="true" aria-labelledby="modal-1-title">
        <header class="modal__header">
          <h2 class="modal__title" id="modal-1-title">
            顧客検索
          </h2>
          <button type="button" @click="toggleStatus" class="modal__close" aria-label="Close modal" data-micromodal-close></button>
        </header>
        <main class="modal__content" id="modal-1-content">
          <div v-if="customers.value" class="lg:w-2/3 w-full mx-auto overflow-auto">
            <table class="table-auto w-full text-left whitespace-no-wrap">
              <thead>
                <tr>
                  <th
                    class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">
                    ID</th>
                  <th class=" py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">
                    Name</th>
                  <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">
                    Kana</th>
                  <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">
                    tel</th>
                </tr>
              </thead>
              <tbody>
                  <tr v-for="customer in customers.value.data" :key="customer.id">
                    <td class="px-4 py-3 border-b-2 border-gray-200">
                      <button type="button" @click="setCustomer({ id: customer.id, kana: customer.kana})" class="text-blue-400">{{ customer.id }}</button>
                    </td>
                    <td class=" py-3 border-b-2 border-gray-200">{{ customer.name }}</td>
                    <td class="px-4 py-3 border-b-2 border-gray-200">{{ customer.kana }}</td>
                    <td class="px-4 py-3 border-b-2 border-gray-200">{{ customer.tel }}</td>
                  </tr>
              </tbody>
            </table>
          </div>
        </main>
        <footer class="modal__footer">
          <button type="button" @click="toggleStatus" class="modal__btn" data-micromodal-close aria-label="Close this dialog window">Close</button>
        </footer>
      </div>
    </div>
  </div>
  <div>
    <input type="text" name="customer" v-model="search" placeholder="カナ or TEL">
    <button type="button" @click="searchCustomers" data-micromodal-trigger="modal-1" class="bg-green-400 hover:bg-blue-800 rounded-lg">検索する</button>
  </div>
</template>
