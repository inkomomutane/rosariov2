<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { t } from '@/lib/utils';
defineProps<{
    name?: string;
}>();

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
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
        </div>
    </AppLayout>
</template>

