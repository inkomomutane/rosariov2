<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { MONEY } from '@/lib/helpers';
import { t } from '@/lib/utils';
import {
    Card,
    CardAction,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle
} from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import {
    DollarSign,
    TrendingUp,
    TrendingDown,
    CreditCard,
    History,
    Download
} from 'lucide-vue-next';
import { Badge } from '@/components/ui/badge';
import { PropType,watch,ref,h } from 'vue';
import { PaymentDataDto, ProfileDto } from '@/types/generated';
import VTableRowAction from '@/components/VTable/VTableRowAction.vue';
import VTableHeader from '@/components/VTable/VTableHeader.vue';
import VPagination from '@/components/VPagination.vue';
import TableListSelect from '@/components/VTable/TableListSelect.vue';
import VTable from '@/components/VTable/VTable.vue';
import ContainerBox from '@/components/Box/ContainerBox.vue';
import { createColumnHelper } from '@tanstack/vue-table';

const props = defineProps({
    doctor: { type: Object as PropType<ProfileDto>, required: true },
    balance: { type: Number, required: true },
    pending_amount: { type: Number, default: 0 },
    withdraw_amount: { type: Number, default: 0 },
    payments: { type: Object, required: true },
});

const breadcrumbs = [
    { title: t('Dashboard'), href: '/dashboard' },
    { title: t('Overview'), href: '/dashboard/balance' },
];

const data = ref([]);

watch(
    () => props.payments?.data,
    (value) => {
        data.value = value ?? [];
    },
    { immediate: true, deep: true },
);
const buildActions = (row: PaymentDataDto) => {
    return [
        {
            label: t('View'),
            icon: Eye,
            onClick: () =>  {},
        },
    ];
};
const columnHelper = createColumnHelper<PaymentDataDto>();
const columns = [
    columnHelper.accessor('invoice_code', {
        header: ({ column }) => h(VTableHeader, { column, title: t('Invoice'), class: 'rounded-none border-none text-muted-foreground' }),
        cell: ({ row }) => h('span', { class: 'ps-4 font-medium' }, row.original.invoice_code || '-'),
        meta: { label: t('Invoice') },
    }),
    columnHelper.accessor('user_name', {
        header: ({ column }) => h(VTableHeader, { column, title: t('User name'), class: 'rounded-none border-none text-muted-foreground' }),
        cell: ({ row }) => h('span', { class: 'ps-4' }, row.original.user_name),
        meta: { label: t('User name') },
    }),
    columnHelper.accessor('transaction_code', {
        header: ({ column }) => h(VTableHeader, { column, title: t('Transaction'), class: 'rounded-none border-none text-muted-foreground' }),
        cell: ({ row }) => h('span', { class: 'ps-4' }, row.original.transaction_code),
        meta: { label: t('Transaction') },
    }),
    columnHelper.accessor('debit', {
        header: ({ column }) => h(VTableHeader, { column, title: t('Debit'), class: 'rounded-none border-none text-muted-foreground' }),
        cell: ({ row }) => h('span', { class: 'ps-4 text-red-500' }, row.original.debit),
        meta: { label: t('Debit') },
    }),
    columnHelper.accessor('credit', {
        header: ({ column }) => h(VTableHeader, { column, title: t('Credit'), class: 'rounded-none border-none text-muted-foreground' }),
        cell: ({ row }) => h('span', { class: 'ps-4 text-green-600' }, row.original.credit),
        meta: { label: t('Credit') },
    }),
    columnHelper.accessor('payment_date', {
        header: ({ column }) => h(VTableHeader, { column, title: t('Payment Date'), class: 'rounded-none border-none text-muted-foreground' }),
        cell: ({ row }) => h('span', { class: 'ps-4' }, row.original.payment_date),
        meta: { label: t('Payment Date') },
    }),
    columnHelper.accessor('type', {
        header: ({ column }) => h(VTableHeader, { column, title: t('Type'), class: 'rounded-none border-none text-muted-foreground' }),
        cell: ({ row }) => h('span', { class: 'ps-4 capitalize' }, row.original.type),
        meta: { label: t('Type') },
    }),
    columnHelper.display({
        id: 'actions',
        enablePinning: true,
        header: () => h('span', {}, t('Actions')),
        cell: ({ row }) => h(VTableRowAction, {
            row: row,
            actions: buildActions(row.original),
        }),
        meta: { label: t('Action') },
    }),
];
</script>

<template>
    <Head :title="t('Overview')" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="min-h-screen pt-6 px-4 md:px-6">
            <div class="max-w-6xl mx-auto space-y-6">
                <!-- Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 *:data-[slot=card]:bg-gradient-to-t *:data-[slot=card]:from-primary/5 *:data-[slot=card]:to-card *:data-[slot=card]:shadow-none *:data-[slot=card]:rounded">

                    <!-- Main Balance -->
                    <Card class="@container/card border-primary/20">
                        <CardHeader>
                            <CardDescription>{{ t('Balance') }}</CardDescription>
                            <CardTitle class="text-2xl font-semibold tabular-nums @[250px]/card:text-3xl">
                                {{ MONEY(props.balance).format() }}
                            </CardTitle>
                            <CardAction>
                                <Badge variant="outline" class="">
                                    {{ t('Active') }}
                                </Badge>
                            </CardAction>
                        </CardHeader>
                        <CardFooter class="flex-col items-start gap-1.5 text-sm">
                            <div class="text-muted-foreground italic">
                                {{ t('User balance') }}
                            </div>
                        </CardFooter>
                    </Card>

                    <!-- Total Credits -->
                    <Card class="@container/card border-primary/20">
                        <CardHeader>
                            <CardDescription>{{ t('Pending payments') }}</CardDescription>
                            <CardTitle class="text-2xl font-semibold tabular-nums @[250px]/card:text-3xl">
                                {{ MONEY(props.pending_amount).format() }}
                            </CardTitle>
                            <CardAction>
                                <Badge variant="outline" class="">
                                    <TrendingUp class="size-3 mr-1" />

                                </Badge>
                            </CardAction>
                        </CardHeader>
                        <CardFooter class="flex-col items-start gap-1.5 text-sm">
                            <div class="text-muted-foreground">
                                {{ t('Payments that are being processed') }}
                            </div>
                        </CardFooter>
                    </Card>

                    <!-- Total Debits -->
                    <Card class="@container/card border-primary/20">
                        <CardHeader>
                            <CardDescription>{{ t('Withdraw amount') }}</CardDescription>
                            <CardTitle class="text-2xl font-semibold tabular-nums @[250px]/card:text-3xl">
                                {{ MONEY(props.withdraw_amount).format() }}
                            </CardTitle>
                            <CardAction>
                                <Badge variant="outline" class="">
                                    <CreditCard class="size-3 mr-1" />
                                    {{ t('Paid') }}
                                </Badge>
                            </CardAction>
                        </CardHeader>
                        <CardFooter class="flex-col items-start gap-1.5 text-sm">

                            <div class="text-muted-foreground">
                                {{ t('Withdrawals processed') }}
                            </div>
                        </CardFooter>
                    </Card>
                </div>


                <!-- Payments Table -->
                <ContainerBox>

                    <template #header-left>
                        <h2 class="text-lg font-bold">
                            {{ t('Payments') }} {{ t('history') }}
                        </h2>
                    </template>


                    <template #content-table>
                        <VTable :pinning="{ left: [], right: ['actions'] }" :columns-defs="columns" v-model="data">
                        </VTable>
                        <VPagination
                            :from="payments?.from"
                            :to="payments?.to"
                            :per_page="payments?.per_page"
                            :total="payments?.total"
                            :links="payments?.links"
                            :first_page_url="payments?.first_page_url"
                            :last_page_url="payments?.last_page_url"
                            :next_page_url="payments?.next_page_url"
                            :prev_page_url="payments?.prev_page_url"
                            class="p-4"
                        />
                    </template>
                </ContainerBox>

            </div>
        </div>
    </AppLayout>
</template>
