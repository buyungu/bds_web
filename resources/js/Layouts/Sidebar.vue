<script setup>
import { useSidebar } from '../composables/useSidebar';
import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";

const { isSidebarOpen } = useSidebar()

const page = usePage();
const user = computed(() => page.props.auth.user);

// Define menus based on user roles
const menus = computed(() => {
  if (!user.value) return [];

  const roleMenus = {
    admin: [
      { name: "Dashboard", route: "admin.dashboard", icon: "fa-home" },
      { name: "Manage Users", route: "admin.users", icon: "fa-users-gear" },
      { name: "Profile Settings", route: "admin.profile", icon: "fa-user-gear" },
      { name: "Manage Events", route: "admin.events", icon: "fa-calendar" },
      { name: "Reports", route: "admin.reports", icon: "fa-chart-line" },
      { name: "Manage Inventory", route: "admin.inventory", icon: "fa-warehouse" },
    ],
    user: [
      { name: "Dashboard", route: "donor.dashboard", icon: "fa-home" },
      { name: "My Donations", route: "donor.donations", icon: "fa-hand-holding-medical" },
      { name: "Events", route: "donor.events", icon: "fa-calendar-check" },
      { name: "Available Requests", route: "donor.requests", icon: "fa-tint" },
      { name: "Request Blood", route: "recipient.request", icon: "fa-plus-circle" },
      { name: "My Requests", route: "recipient.requests", icon: "fa-tint" },
      { name: "Find Donors", route: "recipient.find-donors", icon: "fa-search" },
      { name: "Profile Settings", route: "donor.profile", icon: "fa-user-gear" },

    ],
    hospital: [
      { name: "Dashboard", route: "hospital.dashboard", icon: "fa-home" },
      { name: "Manage Requests", route: "hospital.requests", icon: "fa-tint" },
      { name: "Donor Database", route: "hospital.donors", icon: "fa-users" },
      { name: "Manage Inventory", route: "hospital.inventory", icon: "fa-warehouse" },
      { name: "Manage Events", route: "hospital.events", icon: "fa-calendar" },
      { name: "Profile Settings", route: "hospital.profile", icon: "fa-user-gear" },
      { name: "Reports", route: "hospital.reports", icon: "fa-chart-line" },
    ],
    organization: [
      { name: "Dashboard", route: "organization.dashboard", icon: "fa-home" },
      { name: "Manage Events", route: "organization.events", icon: "fa-calendar-plus" },
      { name: "Profile Settings", route: "organization.profile", icon: "fa-user-gear" },
      { name: "Reports", route: "organization.reports", icon: "fa-chart-line" },
    ],
  };

  return roleMenus[user.value.role] || [];
});

const isActive = (routeName) => route().current(routeName);
</script>

<template>
  <transition name="slide">
  <aside
    v-if="isSidebarOpen"
    class="fixed z-20 flex flex-col w-72 top-0 left-0 h-screen bg-white dark:bg-gray-900 dark:border-gray-800 text-gray-900 border-r border-gray-200"
  >
    <!-- Logo Section -->
    <div class="p-6 ">
      <Link :href="route('home')">
        <img class="dark:hidden" src="../../../public/images/bdslight.png" alt="Logo" width="150" height="40" />
        <img class="hidden dark:block" src="../../../public/images/bdsdark.png" alt="Logo" width="150" height="40" />
      </Link>
    </div>

    <!-- Scrollable Menu -->
<div class="flex-1 overflow-y-auto px-4 pb-8 scrollbar-hide">
      <h2 class="text-gray-400 mb-4 font-medium text-sm">MENU</h2>
      <nav>
        <ul class="space-y-2">
          <li v-for="menu in menus" :key="menu.name">
            <Link
              :href="route(menu.route)"
              class="flex items-center px-4 py-2 rounded-md transition-all duration-200 ease-in-out"
              :class="isActive(menu.route) ? 'bg-blue-100 dark:bg-blue-950 text-blue-500 dark:text-blue-400' : 'hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-500 dark:text-white'"
            >
              <i :class="`fa-solid ${menu.icon} mr-3 text-lg`"></i>
              <span class="font-medium text-sm">{{ menu.name }}</span>
            </Link>
          </li>
        </ul>
      </nav>
    </div>

    <!-- Logout Button (Fixed at Bottom) -->
    <div class="bg-gray-100 dark:bg-gray-700 mb-4 mx-4 rounded-lg hover:bg-blue-200 dark:hover:bg-gray-800">
      <Link
        :href="route('logout')"
        method="post"
        class="flex w-full items-center px-4 py-2 font-medium text-gray-500 dark:text-white hover:text-blue-500 transition duration-200 ease-in-out"
      >
        <i class="fa-solid fa-sign-out-alt mr-3"></i>
        <span>Logout</span>
      </Link>
    </div>
  </aside>
  </transition>
</template>
