<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { ref, watch, h } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import { FullPatientCaseDto, FormUIMap } from '@/types/generated';
import { Pencil, LucideTrash, SearchIcon, CheckCircle } from 'lucide-vue-next';
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


const props = defineProps({
    cases: {
        type: Object,
        required: true
    },
    search: String,
});

const searchTerm = ref('');
watch(searchTerm, (value) => {
    searchTerm.value = value;
    router.visit(
        route('unassigned-cases', {
            search: value ?? '',
        }),
        {
            only: ['cases', 'search'],
            replace: true,
            preserveState: true,
        },
    );
});

const data = ref([]);

watch(
    () => props.cases?.data,
    (value) => {
        data.value = value ?? [];
    },
    { immediate: true, deep: true },
);

const acceptCrudManager = ref(crudManager<FullPatientCaseDto>());

const buildActions = (row: FullPatientCaseDto) => {
    return [
        {
            label: t('Accept'),
            icon: CheckCircle,
            onClick: () => {
                if (confirm(t('Are you sure you want to accept this case?'))) {
                    router.post(route('case-accept', { case: row.id }));
                }
            },
        },
    ];
};

const columnHelper = createColumnHelper<FullPatientCaseDto>();
const columns = [
    columnHelper.accessor('case_code', {
        enableSorting: true,
        enablePinning: true,
        header: ({ column }) => h(VTableHeader,
            {
                column,
                title: t('Code'),
                class: 'rounded-none border-none text-muted-foreground hover:bg-transparent',
            }
        ),
        cell: ({ row }) =>
            h('span', { class: 'line-clamp-1 rounded-none border-none ps-4' }, row.original.case_code),
        meta: { label: t('Code') },
    }),
    columnHelper.accessor('patient_name', {
        enableSorting: true,
        enablePinning: true,
        header: ({ column }) => h(VTableHeader,
            {
                column,
                title: t('Patient Name'),
                class: 'rounded-none border-none text-muted-foreground hover:bg-transparent',
            }
        ),
        cell: ({ row }) =>
            h('span', { class: 'line-clamp-1 rounded-none border-none ps-4' }, row.original.patient_name + ' ' + row.original.patient_last_name),
        meta: { label: t('Patient Name') },
    }),
    columnHelper.accessor('case_type', {
        enableSorting: true,
        header: ({ column }) => h(VTableHeader,
            {
                column,
                title: t('Case Type'),
                class: 'rounded-none border-none text-muted-foreground hover:bg-transparent',
            }
        ),
        cell: ({ row }) =>
            h('span', { class: 'line-clamp-1 rounded-none border-none ps-4' }, row.original.case_type),
        meta: { label: t('Case Type') },
    }),
    columnHelper.accessor('priority', {
        enableSorting: true,
        header: ({ column }) => h(VTableHeader,
            {
                column,
                title: t('Priority'),
                class: 'rounded-none border-none text-muted-foreground hover:bg-transparent',
            }
        ),
        cell: ({ row }) =>
            h('span', { class: 'line-clamp-1 rounded-none border-none ps-4' }, row.original.priority),
        meta: { label: t('Priority') },
    }),
    columnHelper.accessor('case_date', {
        enableSorting: true,
        header: ({ column }) => h(VTableHeader,
            {
                column,
                title: t('Case Date'),
                class: 'rounded-none border-none text-muted-foreground hover:bg-transparent',
            }
        ),
        cell: ({ row }) =>
            h('span', { class: 'line-clamp-1 rounded-none border-none ps-4' }, row.original.case_date),
        meta: { label: t('Case Date') },
    }),
    columnHelper.accessor('title', {
        enableSorting: true,
        header: ({ column }) => h(VTableHeader,
            {
                column,
                title: t('Title'),
                class: 'rounded-none border-none text-muted-foreground hover:bg-transparent',
            }
        ),
        cell: ({ row }) =>
            h('span', { class: 'line-clamp-1 rounded-none border-none ps-4' }, row.original.title),
        meta: { label: t('Title') },
    }),
    columnHelper.display({
        id: 'actions',
        enablePinning: true,
        header: () => h('span', {}, t('Actions')),
        cell: ({ row }) => {
            return h(VTableRowAction, {
                row: row,
                actions: buildActions(row.original),
            })
        },
        meta: { label: t('Action') },
    }),
];
</script>

<template>
    <Head :title="$t('Unassigned Cases')" />
    <AppLayout>
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <ContainerBox>
                <template v-slot:header-left>
                    <div
                        class="relative mt-3 flex w-full flex-col gap-3 lg:mt-0 lg:flex-row lg:justify-end lg:space-x-2">
                        <div class="w-full">
                            <Input :placeholder="t('Search') + '...'" v-model="searchTerm" class="w-full pl-7" />
                            <span class="absolute inset-y-0 start-0 flex items-center justify-center px-2">
                            <SearchIcon :size="15" class="text-muted-foreground" />
                          </span>
                        </div>
                    </div>
                </template>
                <template #content-table>
                    <VTable :pinning="{ left: [], right: ['actions'] }" :columns-defs="columns" v-model="data">
                        <template #fields_visibility="{ table }">
                            <div class="mb-2 flex items-center justify-between px-2">
                                <TableListSelect :table="table" :document_type="'patients'" />
                            </div>
                        </template>
                    </VTable>
                    <VPagination
                        :from="cases?.from"
                        :to="cases.to"
                        :per_page="cases.per_page"
                        :total="cases.total"
                        :links="cases?.links"
                        :first_page_url="cases?.first_page_url"
                        :last_page_url="cases?.last_page_url"
                        :next_page_url="cases.next_page_url"
                        :prev_page_url="cases?.prev_page_url"
                        class="p-4"
                    />
                </template>
            </ContainerBox>
        </div>
    </AppLayout>
</template>

