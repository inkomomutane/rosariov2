
<script setup lang="ts">
import { computed, ref, watch } from 'vue';
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
import { t } from '@/lib/utils';

interface PatientCase {
    id: string;
}

interface InvoiceData {
    id: string;
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
    discount: string | number | null;
    tax: string | number | null;
    subtotal: string | number | null;
    total: string | number | null;
    paid: string | number | null;
    remaining: string | number | null;
    due: string | number | null;
}

interface PaymentForm {
    contact: string;
    invoice_id: string | null;
}

const props = defineProps<{
    case: PatientCase;
    close: () => void;
    openModal: boolean;
}>();

const invoiceData = ref<InvoiceData | null>(null);
const loading = ref(false);
const error = ref('');

const form = useForm<PaymentForm>({
    contact: '',
    invoice_id: null,
});

// Prevent repeated invoice creation within this component instance.
const invoiceCache = new Map<string, InvoiceData>();
const pendingRequests = new Map<string, Promise<InvoiceData>>();

// Identifies the most recent modal request.
let requestVersion = 0;

const remainingAmount = computed(() => {
    const amount = Number(invoiceData.value?.remaining ?? 0);

    return Number.isFinite(amount) ? amount : 0;
});

const canPay = computed(() => {
    return (
        !form.processing &&
        !loading.value &&
        !error.value &&
        !!invoiceData.value?.id &&
        remainingAmount.value > 0 &&
        form.contact.trim().length > 0
    );
});

const formatAmount = (
    amount: string | number | null | undefined,
): string => {
    const value = Number(amount ?? 0);

    return new Intl.NumberFormat('en-GB', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    }).format(Number.isFinite(value) ? value : 0);
};

const formatDate = (
    value: string | null | undefined,
): string => {
    if (!value) return '—';

    const date = new Date(
        `${value.substring(0, 10)}T00:00:00Z`,
    );

    if (Number.isNaN(date.getTime())) return '—';

    return new Intl.DateTimeFormat('en-GB', {
        day: '2-digit',
        month: 'long',
        year: 'numeric',
        timeZone: 'UTC',
    }).format(date);
};

// Create an invoice once per case while the component is mounted.
const createInvoiceRequest = (
    caseId: string,
): Promise<InvoiceData> => {
    const cached = invoiceCache.get(caseId);

    if (cached) {
        return Promise.resolve(cached);
    }

    const pending = pendingRequests.get(caseId);

    if (pending) {
        return pending;
    }

    const request = axios
        .post<InvoiceData>(
            route('second-review-submit', {
                case: caseId,
            }),
        )
        .then((response) => {
            const invoice = response.data;

            if (!invoice?.id) {
                throw new Error(
                    'Invalid invoice response: missing invoice ID.',
                );
            }

            invoiceCache.set(caseId, invoice);

            return invoice;
        })
        .finally(() => {
            pendingRequests.delete(caseId);
        });

    pendingRequests.set(caseId, request);

    return request;
};

// POST second-review-submit to create the invoice.
const createInvoice = async () => {
    const caseId = props.case?.id;

    if (!caseId) return;

    const currentVersion = ++requestVersion;

    loading.value = true;
    error.value = '';
    invoiceData.value = null;

    form.clearErrors();

    try {
        const invoice = await createInvoiceRequest(caseId);

        // Ignore outdated responses.
        if (
            currentVersion !== requestVersion ||
            !props.openModal ||
            props.case.id !== caseId
        ) {
            return;
        }

        invoiceData.value = invoice;
        form.invoice_id = invoice.id;
    } catch (err) {
        if (currentVersion !== requestVersion) return;

        console.error('Error creating invoice:', err);

        if (axios.isAxiosError(err)) {
            error.value =
                err.response?.data?.message ||
                t('Failed to create invoice.');
        } else {
            error.value = t('Failed to create invoice.');
        }
    } finally {
        if (currentVersion === requestVersion) {
            loading.value = false;
        }
    }
};

// Submit M-PESA payment.
const payInvoice = () => {
    if (!canPay.value || !invoiceData.value) return;

    const invoice = invoiceData.value;
    const caseId = props.case.id;

    form.invoice_id = invoice.id;

    form.post(
        route('pay-my-case-invoice', {
            case: caseId,
        }),
        {
            preserveScroll: true,

            onSuccess: () => {
                form.reset();
                form.clearErrors();

                invoiceCache.delete(caseId);
                invoiceData.value = null;

                props.close();
            },

            onError: (errors) => {
                console.error(
                    'Payment validation failed:',
                    errors,
                );
            },
        },
    );
};

const closeModal = () => {
    if (form.processing) return;

    ++requestVersion;

    loading.value = false;
    error.value = '';

    form.clearErrors();

    props.close();
};

watch(
    () => [props.openModal, props.case?.id] as const,
    ([open]) => {
        if (open) {
            form.reset();
            form.clearErrors();

            void createInvoice();
        } else {
            ++requestVersion;
        }
    },
    { immediate: true },
);
</script>

<template>
    <Dialog
        :open="openModal"
        @update:open="
            (open) => {
                if (!open) closeModal();
            }
        "
    >
        <DialogScrollContent class="max-w-md">
            <DialogHeader>
                <DialogTitle>
                    {{ t('Pay invoice') }}
                </DialogTitle>
            </DialogHeader>

            <div class="space-y-6 py-4">
                <!-- CREATING INVOICE -->
                <div
                    v-if="loading"
                    class="space-y-4 rounded-xl bg-muted/60 p-4"
                >
                    <p class="text-sm text-muted-foreground">
                        {{ t('Preparing invoice...') }}
                    </p>

                    <Skeleton class="h-4 w-full" />
                    <Skeleton class="h-4 w-3/4" />
                    <Skeleton class="h-4 w-full" />
                    <Skeleton class="h-8 w-1/2" />
                </div>

                <!-- ERROR -->
                <div
                    v-else-if="error"
                    class="rounded-lg border border-destructive/30 p-4 text-sm text-destructive"
                >
                    <p>{{ error }}</p>

                    <Button
                        variant="outline"
                        size="sm"
                        class="mt-3"
                        @click="createInvoice"
                    >
                        {{ t('Try again') }}
                    </Button>
                </div>

                <!-- CREATED INVOICE -->
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

                        <div class="border-t pt-3" />

                        <div class="flex justify-between gap-4">
                            <span class="text-muted-foreground">
                                {{ t('Subtotal') }}
                            </span>

                            <span class="font-mono font-medium tabular-nums">
                                {{ formatAmount(invoiceData.subtotal) }}
                            </span>
                        </div>

                        <div class="flex justify-between gap-4">
                            <span class="text-muted-foreground">
                                {{ t('Discount') }}
                            </span>

                            <span class="font-mono font-medium tabular-nums">
                                {{ formatAmount(invoiceData.discount) }}
                            </span>
                        </div>

                        <div class="flex justify-between gap-4">
                            <span class="text-muted-foreground">
                                {{ t('Tax') }}
                            </span>

                            <span class="font-mono font-medium tabular-nums">
                                {{ formatAmount(invoiceData.tax) }}
                            </span>
                        </div>

                        <div class="flex justify-between gap-4">
                            <span class="text-muted-foreground">
                                {{ t('Invoice total') }}
                            </span>

                            <span class="font-mono font-medium tabular-nums">
                                {{ formatAmount(invoiceData.total) }}
                            </span>
                        </div>

                        <div class="flex justify-between gap-4">
                            <span class="text-muted-foreground">
                                {{ t('Already paid') }}
                            </span>

                            <span class="font-mono font-medium tabular-nums">
                                {{ formatAmount(invoiceData.paid) }}
                            </span>
                        </div>
                    </div>

                    <!-- TOTAL TO PAY -->
                    <div class="border-t pt-4">
                        <p class="mb-1 text-xs text-muted-foreground">
                            {{ t('Total to pay') }}
                        </p>

                        <p class="font-mono text-3xl font-bold tracking-tight tabular-nums">
                            {{ formatAmount(invoiceData.remaining) }}
                            <span class="text-sm font-medium text-muted-foreground">
                                MZN
                            </span>
                        </p>
                    </div>
                </div>

                <!-- INVOICE NOTES -->
                <p
                    v-if="invoiceData?.notes && !loading"
                    class="text-xs leading-relaxed text-muted-foreground"
                >
                    {{ invoiceData.notes }}
                </p>

                <!-- M-PESA CONTACT -->
                <div
                    v-if="invoiceData && !loading && !error"
                    class="space-y-2"
                >
                    <Label for="contact">
                        {{ t('Contact') }} - {{ t('M-PESA') }}
                    </Label>

                    <Input
                        id="contact"
                        v-model="form.contact"
                        type="tel"
                        inputmode="tel"
                        autocomplete="tel"
                        placeholder="84XXXXXXX"
                        :disabled="form.processing"
                    />

                    <InputError
                        :message="form.errors.contact"
                    />
                </div>

                <InputError
                    :message="form.errors.invoice_id"
                />
            </div>

            <DialogFooter class="gap-2 sm:gap-3">
                <Button
                    variant="outline"
                    type="button"
                    :disabled="form.processing"
                    @click="closeModal"
                >
                    {{ t('Cancel') }}
                </Button>

                <Button
                    type="button"
                    class="min-w-32"
                    :disabled="!canPay"
                    @click="payInvoice"
                >
                    <span v-if="form.processing">
                        {{ t('Processing...') }}
                    </span>

                    <span v-else-if="loading">
                        {{ t('Preparing...') }}
                    </span>

                    <span v-else>
                        {{ t('Pay') }}
                    </span>
                </Button>
            </DialogFooter>
        </DialogScrollContent>
    </Dialog>
</template>
