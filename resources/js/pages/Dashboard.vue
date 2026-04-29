<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { InfiniteScroll } from "@inertiajs/vue3";
import { t } from '@/lib/utils';
import AsideDashboard from '@/pages/AsideDashboard.vue';
import CaseShortComponent from '@/pages/CaseShortComponent.vue';

const props = defineProps({
    cases: Object,
    userType: String,
    doctorStats: Object,
    patientStats: Object,
    user : Object,
});

const breadcrumbs = [
    {
        title: t('Dashboard'),
        href: '/dashboard',
    },
];
</script>

<template>
    <Head :title="$t('Dashboard')" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="min-h-screen  pt-4 px-2 md:px-4">
            <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-12 gap-5">
                <AsideDashboard :user="user" />
                <main class="col-span-1 md:col-span-6 space-y-4">
                    <InfiniteScroll data="cases">
                        <div v-for="medicalCase in cases.data" :key="medicalCase.id">
                                <CaseShortComponent :medical-case="medicalCase"  />
                        </div>
                    </InfiniteScroll>
                </main>
                <aside class="hidden md:block md:col-span-3 space-y-3">
                </aside>
            </div>
        </div>
    </AppLayout>
</template>
