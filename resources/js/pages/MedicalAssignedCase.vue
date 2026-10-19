
<script setup lang="ts">
import { computed, type PropType, ref } from 'vue';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent } from '@/components/ui/card';
import { Button } from '@/components/ui/button';

import {
    LucideVerified,
    FileText,
    EyeIcon,
    HandCoins,
    RefreshCw,
    ClipboardCheck,
} from 'lucide-vue-next';

import type { FullPatientCaseDto } from '@/types/generated';
import { crudManager, t } from '@/lib/utils';
import PayCaseInvoice from '@/pages/PayCaseInvoice.vue';

const props = defineProps({
    medicalCase: {
        type: Object as PropType<FullPatientCaseDto>,
        required: true,
    },
});

const emit = defineEmits<{
    review: [medicalCase: FullPatientCaseDto];
    secondReview: [medicalCase: FullPatientCaseDto];
}>();

const statusLabels: Record<string, string> = {
    submitted: 'Submitted',
    paid: 'Paid',
    under_review: 'Under Review',
    reviewed: 'Reviewed',
    closed: 'Closed',
    reopened: 'Reopened',
    cancelled: 'Cancelled',
    second_review_submitted: 'Second Review Submitted',
    second_review_paid: 'Second Review Paid',
    in_secondary_review: 'In Secondary Review',
    secondary_reviewed: 'Secondary Reviewed',
};

const priorityLabels: Record<string, string> = {
    low: 'Low',
    normal: 'Normal',
    emergency: 'Emergency',
    critical: 'Critical',
};

const caseTypeLabels: Record<string, string> = {
    tac: 'CT Scan',
    mri: 'MRI',
    ray_x: 'X-Ray',
    ecography: 'Echography',
    laboratory: 'Laboratory',
};

const formatLabel = (value?: string | null): string => {
    if (!value) return '—';

    return value
        .replace(/[_-]/g, ' ')
        .replace(/\b\w/g, char => char.toUpperCase());
};

const status = computed(() => props.medicalCase.status ?? '');

const formattedDate = computed(() => {
    if (!props.medicalCase.case_date) {
        return t('No date set');
    }

    return new Date(props.medicalCase.case_date).toLocaleDateString(
        'en-GB',
        {
            day: 'numeric',
            month: 'short',
            year: 'numeric',
        },
    );
});

const formattedStatus = computed(() =>
    t(statusLabels[status.value] ?? formatLabel(status.value)),
);

const formattedPriority = computed(() =>
    t(
        priorityLabels[props.medicalCase.priority ?? ''] ??
        formatLabel(props.medicalCase.priority),
    ),
);

const formattedCaseType = computed(() =>
    t(
        caseTypeLabels[props.medicalCase.case_type ?? ''] ??
        formatLabel(props.medicalCase.case_type),
    ),
);

// Button visibility
const canReview = computed(() =>
    ![
        'closed',
        'reviewed',
        'secondary_reviewed',
        'cancelled',
    ].includes(status.value),
);

</script>

<template>
    <Card
        class="mt-2 rounded border border-zinc-200 py-4 shadow-none dark:border-zinc-800"
    >
        <CardContent class="flex flex-col gap-3 px-4">
            <div class="flex gap-3">
                <div
                    class="bg-secondary text-primary/50 flex size-20 shrink-0 items-center justify-center rounded"
                >
                    <FileText class="size-10" />
                </div>

                <div class="flex flex-1 flex-col gap-2">
                    <div class="flex items-center gap-2">
                        <p class="text-base font-medium">
                            {{ medicalCase.patient_name }}
                            {{ medicalCase.patient_last_name }}
                        </p>

                        <LucideVerified
                            v-if="medicalCase.patient_verified"
                            class="size-4 shrink-0"
                        />
                    </div>

                    <p class="text-muted-foreground text-sm font-semibold">
                        {{ formattedDate }}
                    </p>
                </div>
            </div>

            <p
                class="text-muted-foreground text-sm/relaxed first-letter:uppercase"
            >
                {{ medicalCase.title }}
            </p>

            <div class="flex flex-wrap gap-2 text-xs">
                <Badge variant="outline">
                    {{ formattedPriority }}
                </Badge>

                <Badge variant="outline">
                    {{ formattedCaseType }}
                </Badge>

                <Badge variant="outline">
                    {{ formattedStatus }}
                </Badge>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <!-- View: always visible -->
                <Button
                    variant="outline"
                    as="a"
                    :href="route('case-view', {
                        case: medicalCase.id,
                    })"
                >
                    {{ t('View') }}
                    <EyeIcon class="size-4" />
                </Button>

                <!-- Review -->
                <Button
                    v-if="canReview"
                    variant="outline"
                    as="a"
                    :href="route('case-review', {
                        case: medicalCase.id,
                    })"
                >
                    {{ t('Review') }}
                    <ClipboardCheck class="size-4" />
                </Button>
            </div>
        </CardContent>
    </Card>
</template>
