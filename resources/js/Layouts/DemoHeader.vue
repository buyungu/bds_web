<script setup>
import { useSidebar } from '../composables/useSidebar';
import NotificationMenu from './header/NotificationMenu.vue';
import ThemeToggle from './header/ThemeToggle.vue';
import UserMenu from './header/UserMenu.vue';

const { toggleSidebar } = useSidebar()

</script>

<template>
    <header
        class="sticky z-10 top-0 flex w-full bg-white border-gray-200 z-99999 dark:border-gray-800 dark:bg-gray-900 border-b"
    >
        <div class="flex flex-row items-center justify-between grow px-6 gap-4">
            <div class="">
                <button @click="toggleSidebar">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
            </div>

            <div class="flex items-center w-full gap-4 py-2  justify-end ">
                <ThemeToggle />
                <NotificationMenu />
            </div>
            <UserMenu />
        </div>
    </header>
</template>
