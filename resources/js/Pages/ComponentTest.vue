<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import Label from '@/Components/InputLabel.vue';
import Input from '@/Components/TextInput.vue';

const emitTest = (e) => {console.log(e)}
</script>

<template>
<GuestLayout>
    <Label value="件名">タイトル</Label>
    <Input @update:model-value="emitTest"></Input>
</GuestLayout>
</template>