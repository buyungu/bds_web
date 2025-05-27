<script setup>
    defineProps({
        routeName: String,
        componentName: String,
    });
</script>

<template>
   <Link :href="route(routeName)" class="hover:bg-slate-700 px-3 py-2 rounded-lg"
   :class="{'bg-slate-700': $page.component === componentName}">
        <slot/>
   </Link>
</template>
