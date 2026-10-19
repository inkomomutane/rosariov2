
<script setup lang="ts">
import { ref, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import axios from 'axios';

import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import InputError from '@/components/InputError.vue';
import {
    Dialog,
    DialogFooter,
    DialogHeader,
    DialogScrollContent,
    DialogTitle,
} from '@/components/ui/dialog';
import { Skeleton } from '@/components/ui/skeleton';

import type { PatientCaseRequestDto } from '@/types/generated';
import { t } from '@/lib/utils';

const props = defineProps({
    case: {
        type: Object,
        required: true,
    },
    close: {
        type: Function,
        required: true,
    },
    openModal: {
        type: Boolean,
        required: true,
    },
});

interface InvoiceData {
    id:string;
    date: string | null;
    due_date: string | null;
    paid_date: string | null;
    cancelled_date: string | null;
    code: string | null;
    reference: string | null;
    notes: string | null;
    status: string | null;
    patient_name: string | null;
    case_title: string | null;
    discount: string | null;
    tax: string | null;
    subtotal: string | null;
    total: string | null;
    paid: string | null;
    remaining: string | null;
    due: string | null;
}

const invoiceData = ref<InvoiceData | null>(null);
const loading = ref(false);
const error = ref('');

const form = useForm<PatientCaseRequestDto>({
    contact: '',
    invoice_id: null,
});

const formatAmount = (amount: string | number | null | undefined) => {
    const value = Number(amount ?? 0);

    return new Intl.NumberFormat('en-GB', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    }).format(Number.isFinite(value) ? value : 0);
};

const formatDate = (value: string | null | undefined) => {
    if (!value) return '—';

    const date = new Date(`${value.substring(0, 10)}T00:00:00Z`);

    if (Number.isNaN(date.getTime())) return '—';

    return new Intl.DateTimeFormat('en-GB', {
        day: '2-digit',
        month: 'long',
        year: 'numeric',
        timeZone: 'UTC',
    }).format(date);
};

const fetchInvoice = async () => {
    if (!props.case?.id) return;

    loading.value = true;
    error.value = '';
    invoiceData.value = null;

    try {
        const response = await axios.get(
            route('get-case-invoice-data', {
                case: props.case.id,
            }),
        );

        const invoice: InvoiceData = response.data ?? response.data;

        invoiceData.value = invoice;
        form.amount = Number(invoice.remaining ?? 0);
    } catch (err) {
        console.error('Error fetching invoice:', err);
        error.value = t('Failed to load invoice details.');
    } finally {
        loading.value = false;
    }
};

const payInvoice = () => {
    form.invoice_id = invoiceData.value?.id ?? null;
    form.post(route('pay-my-case-invoice', { case: props.case.id }), {
        onSuccess: () => {
            props.close();
        },
    });

};

watch(
    () => [props.openModal, props.case?.id] as const,
    ([open]) => {
        if (open) {
            void fetchInvoice();
        }
    },
    { immediate: true },
);
</script>

<template>
    <Dialog
        :open="openModal"
        @update:open="(open) => { if (!open) close(); }"
    >
        <DialogScrollContent class="max-w-md">
            <DialogHeader>
                <DialogTitle>{{ t('Pay invoice') }}</DialogTitle>
            </DialogHeader>

            <div class="space-y-6 py-4">

                <!-- LOADING INVOICE -->
                <div
                    v-if="loading"
                    class="space-y-4 rounded-xl bg-muted/60 p-4"
                >
                    <Skeleton class="h-4 w-full" />
                    <Skeleton class="h-4 w-3/4" />
                    <Skeleton class="h-4 w-full" />
                    <Skeleton class="h-8 w-1/2" />
                </div>

                <!-- ERROR -->
                <div
                    v-else-if="error"
                    class="rounded-lg border p-4 text-sm text-destructive"
                >
                    <p>{{ error }}</p>

                    <Button
                        variant="outline"
                        size="sm"
                        class="mt-3"
                        @click="fetchInvoice"
                    >
                        {{ t('Try again') }}
                    </Button>
                </div>

                <!-- INVOICE DETAILS -->
                <div
                    v-else-if="invoiceData"
                    class="space-y-4 rounded-xl bg-muted/60 p-4"
                >
                    <div class="space-y-3 text-sm">

                        <div class="flex justify-between gap-4">
                            <span class="text-muted-foreground">
                                {{ t('Invoice number') }}
                            </span>
                            <span class="text-right font-medium">
                                {{ invoiceData.code || '—' }}
                            </span>
                        </div>

                        <div class="flex justify-between gap-4">
                            <span class="text-muted-foreground">
                                {{ t('Patient') }}
                            </span>
                            <span class="text-right font-medium">
                                {{ invoiceData.patient_name || '—' }}
                            </span>
                        </div>

                        <div
                            v-if="invoiceData.case_title"
                            class="flex justify-between gap-4"
                        >
                            <span class="shrink-0 text-muted-foreground">
                                {{ t('Case') }}
                            </span>
                            <span class="text-right font-medium">
                                {{ invoiceData.case_title }}
                            </span>
                        </div>

                        <div class="flex justify-between gap-4">
                            <span class="text-muted-foreground">
                                {{ t('Invoice date') }}
                            </span>
                            <span class="font-medium">
                                {{ formatDate(invoiceData.date) }}
                            </span>
                        </div>

                        <div class="flex justify-between gap-4">
                            <span class="text-muted-foreground">
                                {{ t('Due date') }}
                            </span>
                            <span class="font-medium">
                                {{ formatDate(invoiceData.due_date) }}
                            </span>
                        </div>

                        <div class="flex justify-between gap-4">
                            <span class="text-muted-foreground">
                                {{ t('Subtotal') }}
                            </span>
                            <span class="font-medium tabular-nums font-mono">
                                {{ formatAmount(invoiceData.subtotal) }}
                            </span>
                        </div>

                        <div class="flex justify-between gap-4">
                            <span class="text-muted-foreground">
                                {{ t('Discount') }}
                            </span>
                            <span class="font-medium tabular-nums font-mono">
                                {{ formatAmount(invoiceData.discount) }}
                            </span>
                        </div>

                        <div class="flex justify-between gap-4">
                            <span class="text-muted-foreground">
                                {{ t('Tax') }}
                            </span>
                            <span class="font-medium tabular-nums font-mono">
                                {{ formatAmount(invoiceData.tax) }}
                            </span>
                        </div>

                        <div class="flex justify-between gap-4">
                            <span class="text-muted-foreground">
                                {{ t('Invoice total') }}
                            </span>
                            <span class="font-medium tabular-nums font-mono">
                                {{ formatAmount(invoiceData.total) }}
                            </span>
                        </div>

                        <div class="flex justify-between gap-4">
                            <span class="text-muted-foreground">
                                {{ t('Already paid') }}
                            </span>
                            <span class="font-medium tabular-nums font-mono">
                                {{ formatAmount(invoiceData.paid) }}
                            </span>
                        </div>

                    </div>

                    <!-- LARGE TOTAL, LIKE SCREENSHOT -->
                    <div class="border-t pt-4">
                        <p class="mb-1 text-xs text-muted-foreground">
                            {{ t('Total to pay') }}
                        </p>

                        <p class="text-3xl font-bold tracking-tight tabular-nums font-mono">
                            {{ formatAmount(invoiceData.remaining) }}
                        </p>
                    </div>
                </div>

                <!-- ADDITIONAL INFORMATION -->
                <p
                    v-if="invoiceData?.notes"
                    class="text-xs leading-relaxed text-muted-foreground"
                >
                    {{ invoiceData.notes }}
                </p>
                <!-- ONLY EDITABLE FIELD -->
                <div class="space-y-2">
                    <Label for="contact">
                        {{ t('Contact') }} - {{ t('M-PESA') }}
                    </Label>

                    <Input
                        id="contact"
                        v-model="form.contact"
                        type="text"
                        :placeholder="t('84XXXXXXX')"
                    />

                    <InputError :message="form.errors.contact" />
                </div>

                <InputError :message="form.errors.amount" />
            </div>

            <DialogFooter class="gap-2 sm:gap-3">
                <Button
                    variant="outline"
                    type="button"
                    @click="close"
                >
                    {{ t('Cancel') }}
                </Button>

                <Button
                    type="button"
                    class="min-w-32"
                    :disabled="
                        form.processing ||
                        loading ||
                        !invoiceData ||
                        form.amount <= 0
                    "
                    @click="payInvoice"
                >
                    <span v-if="form.processing">
                        {{ t('Processing...') }}
                    </span>
                    <span v-else>
                        {{ t('Pay') }}
                    </span>
                </Button>
            </DialogFooter>
        </DialogScrollContent>
    </Dialog>
</template>
