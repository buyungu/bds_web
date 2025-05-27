<script setup>

defineProps({
  status: String, // Success message text
});

</script>

<template>
    <div
      v-if="status"
      class="flex items-center p-4 mb-4 text-sm rounded-lg bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300"
      role="alert"
    >
      <i class="fa-solid fa-circle-check mr-3"></i>
      <span class="flex-1">{{ status }}</span>

    </div>
</template>
