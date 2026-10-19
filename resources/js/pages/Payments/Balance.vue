
<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { MONEY } from '@/lib/helpers';
import { t } from '@/lib/utils';

import {
    Card,
    CardAction,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';

import { Badge } from '@/components/ui/badge';

import {
    ArrowDownLeft,
    ArrowUpRight,
    CreditCard,
    Eye,
    History,
    Wallet,
} from 'lucide-vue-next';

import { computed, h, ref, watch, type PropType } from 'vue';
import type { PaymentDataDto, ProfileDto } from '@/types/generated';

import VTableRowAction from '@/components/VTable/VTableRowAction.vue';
import VTableHeader from '@/components/VTable/VTableHeader.vue';
import VPagination from '@/components/VPagination.vue';
import VTable from '@/components/VTable/VTable.vue';
import ContainerBox from '@/components/Box/ContainerBox.vue';

import { createColumnHelper } from '@tanstack/vue-table';

const props = defineProps({
    doctor: {
        type: Object as PropType<ProfileDto>,
        required: true,
    },
    balance: {
        type: Number,
        required: true,
    },
    pending_amount: {
        type: Number,
        default: 0,
    },
    withdraw_amount: {
        type: Number,
        default: 0,
    },
    payments: {
        type: Object,
        required: true,
    },
});

const breadcrumbs = [
    { title: t('Dashboard'), href: '/dashboard' },
    { title: t('Overview'), href: '/dashboard/balance' },
];

const data = ref<PaymentDataDto[]>([]);

watch(
    () => props.payments?.data,
    (value) => {
        data.value = value ?? [];
    },
    { immediate: true },
);

// ---------------------------------------------
// Formatting
// ---------------------------------------------

const locale = 'en-GB';

const dateFormatter = new Intl.DateTimeFormat(locale, {
    day: '2-digit',
    month: 'short',
    year: 'numeric',
    timeZone: 'Africa/Maputo',
});

const timeFormatter = new Intl.DateTimeFormat(locale, {
    hour: '2-digit',
    minute: '2-digit',
    hour12: false,
    timeZone: 'Africa/Maputo',
});

const formatDate = (value: string | null | undefined) => {
    if (!value) return '—';

    // Preserve calendar dates without timezone shifts.
    if (/^\d{4}-\d{2}-\d{2}$/.test(value)) {
        const [year, month, day] = value.split('-').map(Number);
        const date = new Date(Date.UTC(year, month - 1, day, 12));

        return dateFormatter.format(date);
    }

    const date = new Date(value);

    return Number.isNaN(date.getTime())
        ? '—'
        : dateFormatter.format(date);
};

const formatTime = (value: string | null | undefined) => {
    if (!value || !/[T ]\d{2}:\d{2}/.test(value)) {
        return null;
    }

    const date = new Date(value);

    return Number.isNaN(date.getTime())
        ? null
        : timeFormatter.format(date);
};

const formatMoney = (value: number | string | null | undefined) => {
    if(!value || isNaN(Number(value)) || MONEY(value).value === 0) return '—';


    return MONEY(Number(value ?? 0)).format();
};

const formatType = (value: string | null | undefined) => {
    if (!value) return '—';

    return t(
        value
            .replace(/[_-]/g, ' ')
            .replace(/\b\w/g, (character) => character.toUpperCase()),
    );
};

const totalPayments = computed(() => props.payments?.total ?? 0);

// ---------------------------------------------
// Actions
// ---------------------------------------------

const buildActions = (row: PaymentDataDto) => [
    {
        label: t('View'),
        icon: Eye,
        onClick: () => {},
    },
];

// ---------------------------------------------
// Table
// ---------------------------------------------

const columnHelper = createColumnHelper<PaymentDataDto>();

const header = (column: any, title: string) =>
    h(VTableHeader, {
        column,
        title: t(title),
        class: 'rounded-none border-none text-muted-foreground',
    });

const moneyCell = (
    value: number | string | null | undefined,
    colour: string,
) =>
    h(
        'div',
        {
            class: `px-4 text-right font-mono text-xs sm:text-sm font-medium tabular-nums whitespace-nowrap ${colour}`,
        },
        formatMoney(value),
    );

const columns = [
    columnHelper.accessor('invoice_code', {
        header: ({ column }) => header(column, 'Invoice'),
        cell: ({ row }) =>
            h(
                'span',
                {
                    class: 'ps-4 font-medium text-foreground whitespace-nowrap',
                },
                row.original.invoice_code || '—',
            ),
        meta: { label: t('Invoice') },
    }),

    columnHelper.accessor('user_name', {
        header: ({ column }) => header(column, 'User name'),
        cell: ({ row }) =>
            h(
                'span',
                {
                    class: 'ps-4 text-sm text-foreground whitespace-nowrap',
                },
                row.original.user_name || '—',
            ),
        meta: { label: t('User name') },
    }),

    columnHelper.accessor('transaction_code', {
        header: ({ column }) => header(column, 'Transaction'),
        cell: ({ row }) =>
            h(
                'span',
                {
                    class: 'ps-4 font-mono text-xs text-muted-foreground whitespace-nowrap',
                },
                row.original.transaction_code || '—',
            ),
        meta: { label: t('Transaction') },
    }),

    columnHelper.accessor('debit', {
        header: ({ column }) => header(column, 'Debit'),
        cell: ({ row }) =>
            moneyCell(
                row.original.debit,
                '',
            ),
        meta: { label: t('Debit') },
    }),

    columnHelper.accessor('credit', {
        header: ({ column }) => header(column, 'Credit'),
        cell: ({ row }) =>
            moneyCell(
                row.original.credit,
                '',
            ),
        meta: { label: t('Credit') },
    }),

    columnHelper.accessor('payment_date', {
        header: ({ column }) => header(column, 'Payment Date'),
        cell: ({ row }) => {
            const value = row.original.payment_date;
            return h(
                'div',
                {
                    class: 'flex flex-col gap-0.5 ps-4 whitespace-nowrap',
                },
                [
                    h(
                        'span',
                        {
                            class: 'text-sm font-medium text-foreground tabular-nums',
                        },
                        formatDate(value),
                    ),
                ],
            );
        },
        meta: { label: t('Payment Date') },
    }),

    columnHelper.accessor('type', {
        header: ({ column }) => header(column, 'Type'),
        cell: ({ row }) =>
            h(
                Badge,
                {
                    variant: 'secondary',
                    class: 'ms-4 rounded-md px-2 py-0.5 text-xs font-medium whitespace-nowrap',
                },
                () => formatType(row.original.type),
            ),
        meta: { label: t('Type') },
    }),

    columnHelper.display({
        id: 'actions',
        enablePinning: true,
        header: () =>
            h(
                'span',
                { class: 'text-xs text-muted-foreground' },
                t('Actions'),
            ),
        cell: ({ row }) =>
            h(VTableRowAction, {
                row,
                actions: buildActions(row.original),
            }),
        meta: { label: t('Action') },
    }),
];
</script>

<template>
    <Head :title="t('Overview')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="min-h-screen px-4 py-6 md:px-6">
            <div class="mx-auto max-w-6xl space-y-7">

                <!-- Page heading -->
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div class="space-y-1">
                        <h1 class="text-2xl font-semibold tracking-tight">
                            {{ t('Finanças') }}
                        </h1>
                        <p class="text-sm text-muted-foreground">
                            {{ t('Extracto de pagamentos') }}
                        </p>
                    </div>

                    <Badge
                        variant="outline"
                        class="gap-1.5 rounded-md px-3 py-1.5 text-xs font-normal"
                    >
                        <span class="size-1.5 rounded-full bg-emerald-500" />
                        {{ t('Active') }}
                    </Badge>
                </div>

                <!-- Financial summary -->
                <div
                    class="grid grid-cols-1 gap-4 md:grid-cols-3
                           *:data-[slot=card]:rounded-lg
                           *:data-[slot=card]:shadow-none"
                >
                    <!-- Balance -->
                    <Card
                        class="@container/card border-primary/20
                               bg-gradient-to-br from-primary/5 via-card to-card"
                    >
                        <CardHeader class="gap-3">
                            <CardDescription class="text-sm font-medium">
                                {{ t('Balance') }}
                            </CardDescription>

                            <CardTitle
                                class="font-mono text-2xl font-semibold
                                       tracking-tight tabular-nums
                                       @[250px]/card:text-3xl"
                            >
                                {{ formatMoney(balance) }}
                            </CardTitle>

                            <CardAction>
                                <div
                                    class="flex size-9 items-center justify-center
                                           rounded-lg bg-primary/10 text-primary"
                                >
                                    <Wallet class="size-4" />
                                </div>
                            </CardAction>
                        </CardHeader>

                        <CardFooter class="pt-0">
                            <div class="flex items-center gap-2 text-xs text-muted-foreground">
                                <span class="size-1.5 rounded-full bg-emerald-500" />
                                {{ t('User balance') }}
                            </div>
                        </CardFooter>
                    </Card>

                    <!-- Pending -->
                    <Card class="@container/card">
                        <CardHeader class="gap-3">
                            <CardDescription class="text-sm font-medium">
                                {{ t('Pending payments') }}
                            </CardDescription>

                            <CardTitle
                                class="font-mono text-2xl font-semibold
                                       tracking-tight tabular-nums
                                       @[250px]/card:text-3xl"
                            >
                                {{ formatMoney(pending_amount) }}
                            </CardTitle>

                            <CardAction>
                                <div
                                    class="flex size-9 items-center justify-center
                                           rounded-lg bg-amber-500/10
                                           text-amber-600 dark:text-amber-400"
                                >
                                    <History class="size-4" />
                                </div>
                            </CardAction>
                        </CardHeader>

                        <CardFooter class="pt-0">
                            <div class="flex items-center gap-2 text-xs text-muted-foreground">
                                <span class="size-1.5 rounded-full bg-amber-500" />
                                {{ t('Payments that are being processed') }}
                            </div>
                        </CardFooter>
                    </Card>

                    <!-- Withdrawals -->
                    <Card class="@container/card">
                        <CardHeader class="gap-3">
                            <CardDescription class="text-sm font-medium">
                                {{ t('Withdraw amount') }}
                            </CardDescription>

                            <CardTitle
                                class="font-mono text-2xl font-semibold
                                       tracking-tight tabular-nums
                                       @[250px]/card:text-3xl"
                            >
                                {{ formatMoney(withdraw_amount) }}
                            </CardTitle>

                            <CardAction>
                                <div
                                    class="flex size-9 items-center justify-center
                                           rounded-lg bg-emerald-500/10
                                           text-emerald-600 dark:text-emerald-400"
                                >
                                    <ArrowUpRight class="size-4" />
                                </div>
                            </CardAction>
                        </CardHeader>

                        <CardFooter class="pt-0">
                            <div class="flex items-center gap-2 text-xs text-muted-foreground">
                                <CreditCard class="size-3.5" />
                                {{ t('Withdrawals processed') }}
                            </div>
                        </CardFooter>
                    </Card>
                </div>

                <!-- Payments history -->
                <ContainerBox>
                    <template #header-left>
                        <div class="flex items-center gap-3 py-1">
                            <div
                                class="flex size-9 shrink-0 items-center
                                       justify-center rounded-lg
                                       border bg-muted/40"
                            >
                                <History class="size-4 text-muted-foreground" />
                            </div>

                            <div class="space-y-0.5">
                                <h2 class="text-base font-semibold tracking-tight">
                                    {{ t('Payments') }} {{ t('history') }}
                                </h2>

                                <p class="text-xs text-muted-foreground">
                                    {{ totalPayments }}
                                    {{ t('transactions') }}
                                </p>
                            </div>
                        </div>
                    </template>

                    <template #content-table>
                        <VTable
                            v-model="data"
                            :columns-defs="columns"
                            :pinning="{ left: [], right: ['actions'] }"
                        />

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
                            class="border-t p-4"
                        />
                    </template>
                </ContainerBox>
            </div>
        </div>
    </AppLayout>
</template>
