<script setup>
import { Head } from '@inertiajs/vue3';


defineProps({
    var1: {
        type: Number,
    },
    var2: {
        type: String,
    },
    status: {
        type: String,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <Head title="Test head" />
    <p>{{var1}}</p>
    <p>{{var2}}</p>
    <p>{{status}}</p>
    asdasdasdas
    d
    asd
    asd
    asd
    as
    das
    d
    <pre>
        asd
        as
        das
        da
        sd
        asd
        as
        d
        asd
    </pre>

</template>
