<script setup>
import { useSidebar } from '../../composables/useSidebar';
import DemoHeader from '../../Layouts/DemoHeader.vue';
import Sidebar from '../../Layouts/Sidebar.vue';
import UpdateInfo from '../Profile/Sections/UpdateInfo.vue';
import UpdatePassword from '../Profile/Sections/UpdatePassword.vue';
import DeleteAccount from '../Profile/Sections/DeleteAccount.vue';

defineProps({
    user: Object,
    status: String,
    message: String,
    deleted: String,
});

const { isSidebarOpen } = useSidebar()

</script>

<template>
    <Head title=" | Orgniztion Profile"/>
        <div :class="[isSidebarOpen ? 'ml-72' : 'ml-0', 'transition-all duration-300 ']">

    <DemoHeader />
    <Sidebar />
    <div class="p-8">
        <UpdateInfo :user="user" :status="status"/>
        <UpdatePassword :message="message"/>
        <DeleteAccount :deleted="deleted"/>
    </div>
    </div>
</template>
