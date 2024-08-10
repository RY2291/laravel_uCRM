<script setup>
import { Link } from "@inertiajs/inertia-vue3";
import { ref } from "vue";

const newTitle = ref('');
const newContent = ref('');

</script>

<template>
テスト<br>
<a href="/">aタグ経由</a><br>
<Link href="/">Link経由</Link><br>
<!-- 
:hrefのコロンは、Vue.jsのバインディングディレクティブの省略形
省略しない場合は、v-bind:href
-->
<Link :href="route('inertia.index')">Link経由</Link><br>
<Link :href="route('inertia.show', {id:1})">ルートパラメータ</Link>

<div class="mb-8">
    <input type="text" name="newTitle" v-model="newTitle">{{ newTitle }}<br>
    <input type="text" name="newContent" v-model="newContent">{{ newContent }}<br>
    <Link as="button" method="post" :href="route('inertia.store')"
        :data="{
            title: newTitle,
            content: newContent
        }"
    >DBへの保存テスト</Link>
</div>

</template>