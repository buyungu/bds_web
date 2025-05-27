<script setup>

defineProps({
  error: String, // Success message text
});

</script>

<template>
    <div
      v-if="error"
      class="flex items-center p-4 mb-4 text-sm rounded-lg bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300"
      role="alert"
    >
    <i class="fa-solid fa-circle-xmark mr-3"></i>
    <span class="flex-1">{{ error }}</span>

    </div>
</template>
