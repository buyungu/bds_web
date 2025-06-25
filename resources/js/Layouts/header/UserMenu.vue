<template>
    <div class="relative" ref="dropdownRef">
      <button
        class="flex items-center text-gray-700 dark:text-gray-400 whitespace-nowrap"
        @click.prevent="toggleDropdown"
      >
        <span class="mr-3 overflow-hidden rounded-full h-11 w-11 object-cover object-center">
          <img
            :src="
                user.avatar
                    ? `/storage/${user.avatar}`
                    : '/storage/avatars/default.jpg'
            "
            alt=""
            class="rounded-full h-11 w-11 object-cover object-center"
          />
        </span>

        <p class="mr-3 font-medium text-sm text-blue-500 block truncate max-w-[100px] sm:max-w-[150px]">
            {{ user.name }}
        </p>

        <i class="fa-solid fa-chevron-down text-blue-500" :class="{ 'rotate-180': dropdownOpen }"></i>
      </button>

      <div
        v-if="dropdownOpen"
        class="absolute z-50 right-0 mt-[17px] flex flex-col rounded-2xl border border-gray-200 bg-white p-3 shadow-lg dark:border-gray-800 dark:bg-gray-900
               w-[220px] sm:w-[260px]          /* Adjust width based on breakpoint */
               left-1/2 -translate-x-1/2 sm:left-auto sm:translate-x-0 /* Center on mobile, right on desktop */
               "
        @click.stop
      >
        <div>
          <span class="block font-medium text-gray-700 text-sm dark:text-gray-400">
            {{ user.name }}
          </span>
          <span class="mt-0.5 block text-xs text-gray-500 dark:text-gray-400">
            {{ user.email }}
          </span>
        </div>

        <ul class="flex flex-col gap-1 pt-4 pb-3 border-b border-gray-200 dark:border-gray-800">
          <li v-for="item in menuItems" :key="item.href">
            <Link
              :href="route(item.href)"
              class="flex items-center gap-3 px-3 py-2 font-medium text-gray-700 rounded-lg group text-sm hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300"
            >
              <i :class="`fa-solid fa-${item.icon} text-gray-500 group-hover:text-gray-700 dark:group-hover:text-gray-300`"></i>
              {{ item.text }}
            </Link>
          </li>
        </ul>
        <Link
          :href="route('logout')"
          method="post"
          as="button"
          class="flex items-center gap-3 px-3 py-2 mt-3 font-medium text-gray-700 rounded-lg group text-sm hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300"
        >
          <i class="fa-solid fa-arrow-right-from-bracket text-gray-500 group-hover:text-gray-700 dark:group-hover:text-gray-300"></i>
          Sign out
        </Link>
      </div>
      </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'

const user = usePage().props.auth.user;
const dropdownOpen = ref(false)
const dropdownRef = ref(null)

const menuItems = computed(() => {
  if (!user) return [];

  const roleMenus = {
      admin: [
        { href: 'admin.dashboard', icon: 'house', text: 'Dashboard' },
        { href: 'admin.profile', icon: 'user', text: 'Profile Settings' },
      ],
      user: [
        { href: 'donor.dashboard', icon: 'house', text: 'Dashboard' },
        { href: 'donor.profile', icon: 'user', text: 'Profile Settings' },
      ],
      recipient: [
        { href: 'recipient.dashboard', icon: 'house', text: 'Dashboard' },
        { href: 'recipient.profile', icon: 'user', text: 'Profile Settings' },
      ],
      hospital: [
        { href: 'hospital.dashboard', icon: 'house', text: 'Dashboard' },
        { href: 'hospital.profile', icon: 'user', text: 'Profile Settings' },
      ],
      organization: [
        { href: 'organization.dashboard', icon: 'house', text: 'Dashboard' },
        { href: 'organization.profile', icon: 'user', text: 'Profile Settings' },
      ],
  };

  return roleMenus[user.role] || []
});

const toggleDropdown = (event) => {
  event.stopPropagation() // Prevents closing immediately
  dropdownOpen.value = !dropdownOpen.value
}

const closeDropdown = () => {
  dropdownOpen.value = false
}

const handleClickOutside = (event) => {
  if (dropdownRef.value && !dropdownRef.value.contains(event.target)) {
    closeDropdown()
  }
}

onMounted(() => {
  document.addEventListener('click', handleClickOutside)
})

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside)
})
</script>