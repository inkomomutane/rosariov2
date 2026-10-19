<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import AppLayout from '@/layouts/AppLayout.vue';
import { crudManager, t } from '@/lib/utils';
import AsideDashboard from '@/pages/AsideDashboard.vue';
import CaseShortComponent from '@/pages/MedicalCase.vue';
import { FullPatientCaseDto } from '@/types/generated';
import { Head, InfiniteScroll, router } from '@inertiajs/vue3';
import { Plus, SearchIcon } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import Create from '@/pages/My/Cases/Create.vue';

const props = defineProps({
    cases: Object,
    user: Object,
    case_types: {
        type: Array,
        required: true,
    },
    urgencies: {
        type: Array,
        required: true,
    },
    person: {
        type: Object,
        required: true,
    },
});

const breadcrumbs = [
    {
        title: t('Dashboard'),
        href: '/dashboard',
    },
];

const searchTerm = ref('');
watch(searchTerm, (value) => {
    searchTerm.value = value;
    router.visit(
        route('dashboard', {
            search: value ?? '',
        }),
        {
            only: ['cases', 'search'],
            reset: ['cases'],
            replace: true,
            preserveState: true,
        },
    );
});

const createCrudManager = ref(crudManager<FullPatientCaseDto>());
</script>

<template>
    <Head :title="$t('Dashboard')" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="min-h-screen px-2 pt-4 md:px-4">
            <div class="mx-auto grid max-w-6xl grid-cols-1 gap-5 md:grid-cols-12">
                <AsideDashboard :user="user" />
                <main class="col-span-1 space-y-4 md:col-span-6">
                    <div class="relative mt-3 flex w-full flex-col gap-3 lg:mt-0 lg:flex-row lg:justify-end lg:space-x-2">
                        <div class="w-full">
                            <Input :placeholder="t('Search') + '...'" v-model="searchTerm" class="w-full pl-7" />
                            <span class="absolute inset-y-0 start-0 flex items-center justify-center px-2">
                                <SearchIcon :size="15" class="text-muted-foreground" />
                            </span>
                        </div>
                        <Button variant="outline" @click="createCrudManager.open(null)" size="sm">
                            <Plus size="15" class="" />
                            {{ t('Add') }}
                        </Button>
                    </div>
                    <InfiniteScroll data="cases">
                        <div v-for="medicalCase in cases.data" :key="medicalCase.id">
                            <CaseShortComponent :medical-case="medicalCase" />
                        </div>
                    </InfiniteScroll>
                </main>
                <aside class="hidden space-y-3 md:col-span-3 md:block"></aside>
            </div>
        </div>
    </AppLayout>

    <Create
        v-if="createCrudManager.isModalOpen"
        :urgencies="urgencies"
        :case_types="case_types"
        :open-modal="createCrudManager.isModalOpen"
        :close="createCrudManager.close"
        :person="person"
    />
</template>
