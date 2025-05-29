<script setup>
import { usePage } from '@inertiajs/vue3';
import NavLink from '../Components/NavLink.vue';
import { switchTheme } from '../theme';
import { computed, ref } from 'vue';
import UserMenu from './header/UserMenu.vue';

const page = usePage();
const user = computed(() => page.props.auth.user );

const show = ref(false);
</script>

<template>
    <div class=" flex flex-col">
    <!-- Oerlay -->
     <div v-show="show" @click="show = false" class="fixed inset-0 z-40"></div>
    <div class=" bg-slate-800 text-white">
        <nav class="mx-auto max-w-screen-lg p-2 flex items-center justify-between space-x-6">
            <div class="flex items-center space-x-6">
                <Link :href="route('home')">
                    <img class="w-40 h-15" src="../../../public/images/bdsdark.png" alt="Logo"/>
                </Link>
                <NavLink routeName="home" componentName="Home">Home</NavLink>
                <NavLink routeName="events" componentName="Events">Events</NavLink>
            </div>

            <div class="flex items-center space-x-6">

                <button @click="switchTheme" class=" hover:bg-slate-700 rounded-full grid place-items-center w-6 h-6 hover:outline outline-1 outline-white">
                    <i class="fa-solid fa-circle-half-stroke"></i>
                </button>
                <!---------- Auth ---------->
                <div v-if="user" class="relative">
                    <UserMenu />
                </div>
                <!---------- Guest ---------->
                <div v-else class="space-x-6">
                    <NavLink routeName="login" componentName="Login">Login</NavLink>
                    <NavLink routeName="register" componentName="Register">Register</NavLink>
                </div>
                
            </div>
        </nav>
    </div>

    <main class="mx-auto p-4">
        <slot/>
    </main>

    Footer 
    <footer class="bg-gray-800 text-white py-4">
        <div class="container mx-auto px-6 text-center">
            <p class="mb-4">&copy; {{ new Date().getFullYear() }} Blood Donation System. All rights reserved.</p>
            <div class="flex justify-center space-x-4 text-gray-400">
                <a href="#" class="hover:text-white transition"><i class="fab fa-facebook"></i></a>
                <a href="#" class="hover:text-white transition"><i class="fab fa-twitter"></i></a>
                <a href="#" class="hover:text-white transition"><i class="fab fa-instagram"></i></a>
            </div>
            <div class="text-sm text-gray-400">
                <a href="#" class="px-2 hover:text-white">Privacy</a>|
                <a href="#" class="px-2 hover:text-white">Terms</a>|
                <a href="#" class="px-2 hover:text-white">Contact</a>
            </div>
        </div>
    </footer>
</div>
</template>

