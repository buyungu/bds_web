<script setup>
import { useSidebar } from '../../composables/useSidebar';
import DemoHeader from '../../Layouts/DemoHeader.vue';
import Sidebar from '../../Layouts/Sidebar.vue';
import PaginationLinks from '../../Components/PaginationLinks.vue';
import TextLink from '../../Components/TextLink.vue';

const props = defineProps({
    donations: Object,
})

const formatDate = (date) => new Date(date).toLocaleDateString();

const { isSidebarOpen } = useSidebar()

</script>

<template>
    <Head title=" | Blood Requests" />
    <div :class="[isSidebarOpen ? 'ml-72' : 'ml-0', 'transition-all duration-300 ']">
        <DemoHeader />
        <Sidebar />
        <div class="p-4 sm:p-8">
            <div v-if="props.donations.data.length">
                <div class="mb-6">
                    <!-- Responsive Table -->
                    <div class="overflow-x-auto rounded-md">
                        <table
                            class="min-w-[700px] w-full table-fixed border-collapse overflow-hidden rounded-md text-sm ring-1 ring-slate-300 dark:ring-slate-600 bg-white shadow-lg"
                        >
                            <thead
                                class="bg-slate-300 text-xs uppercase text-slate-600 dark:text-slate-400 dark:bg-slate-900"
                            >
                                <tr>
                                    <th class="p-3 w-2/6 text-left">Recipient name</th>
                                    <th class="p-3 w-2/6 text-left">Hospital name</th>
                                    <th class="p-3 w-2/6 text-left">Location</th>
                                    <th class="p-3 w-1/6 text-left">Blood Type</th>
                                    <th class="p-3 w-1/6 text-left">Quantity</th>
                                    <th class="p-3 w-1/6 text-left">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="donation in props.donations.data"
                                    :key="donation.id"
                                    class="border-b border-slate-200 hover:bg-slate-100 dark:bg-slate-800 dark:hover:bg-slate-600 dark:border-slate-600"
                                >
                                    <td class="p-3 w-2/6 text-left">
                                        <div class="flex items-center gap-2">
                                            <img
                                                :src="
                                                    donation.recipient.avatar
                                                        ? `/storage/${donation.recipient.avatar}`
                                                        : '/storage/avatars/default.jpg'
                                                "
                                                alt=""
                                                class="h-10 w-10 rounded-full object-cover object-center"
                                            />
                                            <h4 class="font-bold">
                                                {{ donation.recipient.name }}
                                            </h4>
                                        </div>
                                    </td>
                                    <td class="p-3 w-2/6 text-left">
                                        <h4 class="font-bold">
                                            {{ donation.hospital.name }}
                                        </h4>
                                    </td>
                                    <td class="p-3 w-2/6 text-left">
                                        <p>{{ donation.hospital.location.address }}</p>
                                    </td>
                                    <td class="py-3 pl-6 w-1/6 text-left">
                                        {{ donation.blood_type }}
                                    </td>
                                    <td class="p-3 w-1/6 text-left">
                                        {{ donation.quantity }}
                                    </td>
                                    <td class="p-3 w-1/6 text-left">
                                        {{ formatDate(donation.created_at) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div>
                    <PaginationLinks :paginator="donations" />
                </div>
            </div>
            <div v-else>
                No blood donations Yet!! view <TextLink routeName="donor.requests" neno="Available blood requests"/>
            </div>
        </div>
    </div>
</template>
