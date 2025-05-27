<script setup>

    defineProps({
        neno: String,
        routeName: String,
    });
</script>

<template>
    <Link :href="route(routeName)" class=" font-medium text-blue-500
     hover:text-blue-600 dark:text-blue-400 dark:hover:text-blue-500 underline">
        {{ neno }}
    </Link>
</template>
