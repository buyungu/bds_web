<script setup>
import { switchTheme } from '../../theme';
</script>

<template>
    <button @click="switchTheme" class=" text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 dark:hover:text-gray-100 rounded-full grid place-items-center w-12 h-12 hover:outline outline-1 outline-gray-200 dark:outline-gray-900">
        <i class="fa-solid fa-circle-half-stroke"></i>
    </button>
</template>
