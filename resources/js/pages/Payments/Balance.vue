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

const props = defineProps({
    doctor: { type: Object, required: true },
    balance: { type: Number, required: true },
    pending_amount: { type: Number, default: 0 },
    withdraw_amount: { type: Number, default: 0 },
});

const breadcrumbs = [
    { title: t('Dashboard'), href: '/dashboard' },
    { title: t('Financial Overview'), href: '/dashboard/balance' },
];
</script>

<template>
    <Head :title="t('Financial Overview')" />
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
            </div>
        </div>
    </AppLayout>
</template>
