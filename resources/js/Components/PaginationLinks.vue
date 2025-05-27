<script setup>
defineProps({
    paginator: Object,
})
</script>

<template>
    <div class="mt-4 flex justify-between">
        <div class="flex">
            <div v-for="(link, index) in paginator.links" :key="index" class="mx-1">
                <Link
                    v-if="link.url"
                    :href="link.url"
                    v-html="link.label"
                    class="px-3 py-1 border rounded bg-gray-200 dark:bg-slate-700 hover:bg-gray-300 dark:hover:bg-slate-600"
                    :class="{ 'font-bold border-blue-500 text-blue-500 dark:text-blue-400': link.active }"
                />
            </div>
        </div>        
        <p>
            Showing {{ paginator.from }} to {{ paginator.to }} of {{ paginator.total }} results
        </p>
    </div>
</template>