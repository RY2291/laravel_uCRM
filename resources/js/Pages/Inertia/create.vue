<script setup>
import { Inertia } from "@inertiajs/inertia";
import { ref } from "vue";
import { reactive } from "vue";

defineProps({
    errors: Object
})

const newTitle = ref('');
const newContent = ref('');

const form = reactive({
    title: null,
    content: null
})

const submitFunc = () =>{
    Inertia.post('/inertia', form)
}
</script>


<template>
    <!-- preventをつけることで、ボタンクリック時にページ読み込みを防止する -->
    <form @submit.prevent="submitFunc">
        <input name="title" v-model="form.title">{{ form.title }}<br>
        <div v-if="errors.title">{{ errors.title }}</div>
        <input name="content" v-model="form.content">{{ form.content }}<br>
        <div v-if="errors.content">{{ errors.content }}</div>
        <button>送信</button>
    </form>
</template>