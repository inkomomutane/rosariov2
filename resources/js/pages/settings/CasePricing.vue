<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { ref, watch, h } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import { CasePricingDto } from '@/types/generated';
import { Pencil, LucideTrash, SearchIcon, Tag } from 'lucide-vue-next';
import { crudManager, t } from '@/lib/utils';
import VTableHeader from '@/components/VTable/VTableHeader.vue';
import ContainerBox from '@/components/Box/ContainerBox.vue';
import TableListSelect from '@/components/VTable/TableListSelect.vue';
import { createColumnHelper } from '@tanstack/vue-table';
import VTableRowAction from '@/components/VTable/VTableRowAction.vue';
import VTable from '@/components/VTable/VTable.vue';
import VPagination from '@/components/VPagination.vue';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import CreateCasePricing from '@/pages/settings/CreateCasePricing.vue';
import EditCasePricing from '@/pages/settings/EditCasePricing.vue';
import DeleteCasePricing from '@/pages/settings/DeleteCasePricing.vue';
import { MONEY } from '@/lib/helpers';
import { Badge } from '@/components/ui/badge';


const props = defineProps({
    pricing: {
        type: Object,
        required: true
    },
    case_types: {
        type: Array,
        required: true
    },
    search: String,
});

const searchTerm = ref(props.search ?? '');

watch(searchTerm, (value) => {
    router.visit(
        route('case-pricing', {
            search: value ?? '',
        }),
        {
            only: ['pricing', 'search'],
            replace: true,
            preserveState: true,
        },
    );
});

const data = ref([]);

watch(
    () => props.pricing?.data,
    (value) => {
        data.value = value ?? [];
    },
    { immediate: true, deep: true },
);

const deleteCrudManager = ref(crudManager<CasePricingDto>());
const createCrudManager = ref(crudManager<CasePricingDto>());
const editCrudManager = ref(crudManager<CasePricingDto>());

const buildActions = (row: CasePricingDto) => {
    return [
        {
            label: t('Edit'),
            icon: Pencil,
            onClick: () => editCrudManager.value.open(row)
        },
        {
            label: t('Delete'),
            icon: LucideTrash,
            onClick: () => deleteCrudManager.value.open(row),
        },
    ];
};

const columnHelper = createColumnHelper<CasePricingDto>();

const columns = [
    columnHelper.accessor('case_type', {
        enableSorting: true,
        header: ({ column }) => h(VTableHeader, {
            column,
            title: t('Case Type'),
            class: 'rounded-none border-none text-muted-foreground hover:bg-transparent'
        }),
        cell: ({ row }) => h(Badge, { variant:'outline', class: 'ms-4 font-medium' }, row.original.case_type),
        meta: { label: t('Case Type') },
    }),
    columnHelper.accessor('price', {
        enableSorting: true,
        header: ({ column }) => h(VTableHeader, {
            column,
            title: t('Price'),
            class: 'rounded-none border-none text-muted-foreground hover:bg-transparent'
        }),
        cell: ({ row }) => h('span', { class: 'ps-4' }, MONEY(row.original.price).format()),
        meta: { label: t('Price') },
    }),
    columnHelper.accessor('review_phase', {
        enableSorting: true,
        header: ({ column }) => h(VTableHeader, {
            column,
            title: t('Review Phase'),
            class: 'rounded-none border-none text-muted-foreground hover:bg-transparent'
        }),
        cell: ({ row }) => h('span', { class: 'ps-4' }, `${t('Phase')} ${row.original.review_phase}`),
        meta: { label: t('Review Phase') },
    }),
    columnHelper.display({
        id: 'actions',
        enablePinning: true,
        header: () => h('span', { class: 'text-muted-foreground' }, t('Actions')),
        cell: ({ row }) => h(VTableRowAction, {
            row: row,
            actions: buildActions(row.original),
        }),
        meta: { label: t('Action') },
    }),
];
</script>
<template>
    <Head :title="$t('Case Pricing Configuration')" />
    <AppLayout>
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <ContainerBox>
                <template v-slot:header-left>
                    <div class="relative mt-3 flex w-full flex-col gap-3 lg:mt-0 lg:flex-row lg:justify-end lg:space-x-2">
                        <div class="relative w-full">
                            <Input :placeholder="t('Search pricing') + '...'" v-model="searchTerm" class="w-full pl-9" />
                            <span class="absolute inset-y-0 start-0 flex items-center justify-center px-3">
                                <SearchIcon :size="16" class="text-muted-foreground" />
                            </span>
                        </div>
                    </div>
                </template>

                <template v-slot:header-right>
                    <Button variant="outline"  @click="createCrudManager.open(null)" size="sm">
                        <Tag class="mr-2 h-4 w-4" />
                        {{ t('Add New Pricing') }}
                    </Button>
                </template>

                <template #content-table>
                    <VTable :pinning="{ left: [], right: ['actions'] }" :columns-defs="columns" v-model="data">
                        <template #fields_visibility="{ table }">
                            <div class="mb-2 flex items-center justify-between px-2">
                                <TableListSelect :table="table" :document_type="'case_pricing'" />
                            </div>
                        </template>
                    </VTable>

                    <VPagination
                        :from="pricing?.from"
                        :to="pricing?.to"
                        :per_page="pricing?.per_page"
                        :total="pricing?.total"
                        :links="pricing?.links"
                        :first_page_url="pricing?.first_page_url"
                        :last_page_url="pricing?.last_page_url"
                        :next_page_url="pricing?.next_page_url"
                        :prev_page_url="pricing?.prev_page_url"
                        class="p-4"
                    />
                </template>
            </ContainerBox>
        </div>
    </AppLayout>

     <CreateCasePricing v-if="createCrudManager.isModalOpen"  :case_types="case_types" :openModal="createCrudManager.isModalOpen" :close="createCrudManager.close" />
    <EditCasePricing v-if="editCrudManager.isModalOpen" :case_types="case_types" :openModal="editCrudManager.isModalOpen" :close="editCrudManager.close" :pricing="editCrudManager.model" />
    <DeleteCasePricing v-if="deleteCrudManager.isModalOpen" :openModal="deleteCrudManager.isModalOpen" :close="deleteCrudManager.close" :pricing="deleteCrudManager.model" />

</template>
