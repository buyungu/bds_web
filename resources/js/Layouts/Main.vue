<script setup>
import { usePage } from '@inertiajs/vue3';
import NavLink from '../Components/NavLink.vue';
import { switchTheme } from '../theme';
import { computed, ref } from 'vue';
import UserMenu from './header/UserMenu.vue';
import { Link } from '@inertiajs/vue3';

const page = usePage();
const user = computed(() => page.props.auth.user );

const showMobileMenu = ref(false);

const closeMobileMenuAndUserMenu = () => {
    showMobileMenu.value = false;
};

// New computed property for mobile menu items based on user role
const mobileMenuItems = computed(() => {
    if (!user.value) return []; // Access user.value

    const roleMenus = {
        admin: [
            { href: 'admin.dashboard', text: 'Dashboard' },
            { href: 'admin.profile', text: 'Profile Settings' },
        ],
        user: [ // Assuming 'user' role is for donor
            { href: 'donor.dashboard', text: 'Dashboard' },
            { href: 'donor.profile', text: 'Profile Settings' },
        ],
        recipient: [
            { href: 'recipient.dashboard', text: 'Dashboard' },
            { href: 'recipient.profile', text: 'Profile Settings' },
        ],
        hospital: [
            { href: 'hospital.dashboard', text: 'Dashboard' },
            { href: 'hospital.profile', text: 'Profile Settings' },
        ],
        organization: [
            { href: 'organization.dashboard', text: 'Dashboard' },
            { href: 'organization.profile', text: 'Profile Settings' },
        ],
    };

    return roleMenus[user.value.role] || []; // Access user.value.role
});
</script>

<template>
    <div class="h-full min-h-screen flex flex-col">
        <div v-show="showMobileMenu" @click="closeMobileMenuAndUserMenu" class="fixed inset-0 z-40 bg-black opacity-50 lg:hidden"></div>

        <div class="bg-slate-800 text-white">
            <nav class="mx-auto max-w-screen-lg p-2 flex items-center justify-between">
                <div class="flex items-center space-x-6">
                    <Link :href="route('home')">
                        <img class="w-40 h-15" src="../../../public/images/bdsdark.png" alt="Logo"/>
                    </Link>
                    <div class="hidden lg:flex items-center space-x-6">
                        <NavLink routeName="home" componentName="Home">Home</NavLink>
                        <NavLink routeName="events" componentName="Events">Events</NavLink>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <button @click="switchTheme" class="hover:bg-slate-700 rounded-full grid place-items-center w-6 h-6 hover:outline outline-1 outline-white">
                        <i class="fa-solid fa-circle-half-stroke"></i>
                    </button>

                    <div class="hidden lg:flex items-center space-x-6">
                        <div v-if="user" class="relative">
                            <UserMenu />
                        </div>
                        <div v-else class="space-x-6">
                            <NavLink routeName="login" componentName="Login">Login</NavLink>
                            <NavLink routeName="register" componentName="Register">Register</NavLink>
                        </div>
                    </div>

                    <button @click="showMobileMenu = !showMobileMenu" class="lg:hidden p-2 rounded-md focus:outline-none focus:ring-2 focus:ring-white">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path v-if="!showMobileMenu" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </nav>

            <transition
                enter-active-class="transition ease-out duration-200 transform"
                enter-from-class="-translate-y-full opacity-0"
                enter-to-class="translate-y-0 opacity-100"
                leave-active-class="transition ease-in duration-150 transform"
                leave-from-class="translate-y-0 opacity-100"
                leave-to-class="-translate-y-full opacity-0"
            >
                <div v-show="showMobileMenu" class="lg:hidden bg-slate-700 pb-4">
                    <div class="flex flex-col items-center space-y-2 py-2">
                        <NavLink routeName="home" componentName="Home" @click="closeMobileMenuAndUserMenu">Home</NavLink>
                        <NavLink routeName="events" componentName="Events" @click="closeMobileMenuAndUserMenu">Events</NavLink>
                    </div>
                    <div class="border-t border-slate-600 my-2"></div>

                    <div v-if="user" class="flex flex-col items-center space-y-2 py-2">
                        <div class="flex items-center gap-2 mb-2">
                            <img
                                :src="
                                    user.avatar
                                        ? `/storage/${user.avatar}`
                                        : '/storage/avatars/default.jpg'
                                "
                                alt=""
                                class="rounded-full h-8 w-8 object-cover object-center"
                            />
                            <span class="text-white font-medium text-sm">{{ user.name }}</span>
                        </div>

                        <NavLink
                            v-for="item in mobileMenuItems"
                            :key="item.href"
                            :routeName="item.href"
                            @click="closeMobileMenuAndUserMenu"
                        >
                            {{ item.text }}
                        </NavLink>

                        <NavLink :href="route('logout')" method="post" as="button" @click="closeMobileMenuAndUserMenu">Log Out</NavLink>
                    </div>
                    <div v-else class="flex flex-col items-center space-y-2 py-2">
                        <NavLink routeName="login" componentName="Login" @click="closeMobileMenuAndUserMenu">Login</NavLink>
                        <NavLink routeName="register" componentName="Register" @click="closeMobileMenuAndUserMenu">Register</NavLink>
                    </div>
                </div>
            </transition>
        </div>

        <main class="mx-auto flex-grow max-w-screen-lg p-4">
            <slot/>
        </main>

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