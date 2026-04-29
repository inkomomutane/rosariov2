<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { t } from '@/lib/utils';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { DollarSign, TrendingUp, CreditCard } from 'lucide-vue-next';
import { ref } from 'vue';

const props = defineProps({
    doctor: {
        type: Object,
        required: true
    },
    balance: {
        type: Number,
        required: true
    },
    total_credit: {
        type: Number,
        required: true
    },
    total_debit: {
        type: Number,
        required: true
    },
    payments: {
        type: Object,
        required: true
    }
});

const breadcrumbs = [
    {
        title: t('Dashboard'),
        href: '/dashboard',
    },
    {
        title: t('Doctor Balance'),
        href: '/dashboard/doctor-balance',
    },
];
</script>

<template>
    <Head :title="$t('Doctor Balance')" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="min-h-screen pt-4 px-2 md:px-4">
            <div class="max-w-6xl mx-auto space-y-6">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <Card>
                        <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                            <CardTitle class="text-sm font-medium">
                                {{ t('Total Credits') }}
                            </CardTitle>
                            <TrendingUp class="h-4 w-4 text-green-600" />
                        </CardHeader>
                        <CardContent>
                            <div class="text-2xl font-bold text-green-600">
                                {{ total_credit.toFixed(2) }}
                            </div>
                            <p class="text-xs text-muted-foreground">
                                {{ t('Amounts received') }}
                            </p>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                            <CardTitle class="text-sm font-medium">
                                {{ t('Current Balance') }}
                            </CardTitle>
                            <DollarSign class="h-4 w-4 text-blue-600" />
                        </CardHeader>
                        <CardContent>
                            <div :class="['text-2xl font-bold', balance >= 0 ? 'text-blue-600' : 'text-red-600']">
                                {{ balance.toFixed(2) }}
                            </div>
                            <p class="text-xs text-muted-foreground">
                                {{ t('Account balance') }}
                            </p>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                            <CardTitle class="text-sm font-medium">
                                {{ t('Total Debits') }}
                            </CardTitle>
                            <CreditCard class="h-4 w-4 text-red-600" />
                        </CardHeader>
                        <CardContent>
                            <div class="text-2xl font-bold text-red-600">
                                {{ total_debit.toFixed(2) }}
                            </div>
                            <p class="text-xs text-muted-foreground">
                                {{ t('Amounts paid out') }}
                            </p>
                        </CardContent>
                    </Card>
                </div>

                <!-- Payment History -->
                <Card>
                    <CardHeader>
                        <CardTitle>{{ t('Payment History') }}</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-4">
                            <div v-for="payment in payments.data" :key="payment.id" class="flex items-center justify-between p-4 border rounded-lg">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-1">
                                        <p class="font-medium">{{ payment.description }}</p>
                                        <p class="text-sm text-muted-foreground">{{ payment.reference }}</p>
                                        <p class="text-xs text-muted-foreground">{{ payment.payment_date }}</p>
                                    </div>
                                </div>
                                <div :class="['text-right font-bold', payment.credit ? 'text-green-600' : 'text-red-600']">
                                    <span v-if="payment.credit">+{{ payment.credit.toFixed(2) }}</span>
                                    <span v-else>-{{ payment.debit.toFixed(2) }}</span>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>

