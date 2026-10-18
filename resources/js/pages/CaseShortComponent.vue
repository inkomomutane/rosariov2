<script setup lang="ts">
import { computed, PropType } from 'vue'
import { Badge } from '@/components/ui/badge';
import { Card, CardContent } from "@/components/ui/card";
import {
    LucideArrowRight,
    LucideVerified,
    Bookmark,
    FileText,
    EyeIcon,
    ReceiptText,
    HandCoins
} from "lucide-vue-next";
import { Button } from '@/components/ui/button';
import { FullPatientCaseDto } from '@/types/generated';
import { t} from '@/lib/utils';


const props = defineProps({
    medicalCase: {
        type: Object as PropType<FullPatientCaseDto>,
        required: true
    }
})


const formattedDate = computed(() => {
    if (!props.medicalCase.case_date) return 'No date set'
    return new Date(props.medicalCase.case_date).toLocaleDateString(undefined, {
        month: 'short',
        day: 'numeric',
        year: 'numeric'
    })
})


</script>

<template>

    <Card class="py-4 shadow-none border border-zinc-200 dark:border-zinc-800 rounded mt-2">
        <CardContent class="flex flex-col gap-3 px-4">
            <div class="flex gap-3">
                <div
                    class="bg-secondary text-primary/50 flex size-20 items-center justify-center rounded"
                >
                    <FileText class="size-10" />
                </div>
                <div class="flex flex-1 flex-col gap-2">
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center gap-1">
                            <p class="text-base font-medium">
                                 {{ medicalCase.patient_name }} {{ medicalCase.patient_last_name }}
                            </p>
                            <LucideVerified class="size-4 shrink-0" v-if="medicalCase.patient_verified" />
                        </div>
                        <div>
                            <Button size="sm" variant="secondary">
                                <span class="text-sm font-medium">Save</span>
                                <Bookmark class="h-4 w-4" />
                            </Button>
                        </div>
                    </div>
                    <div class="text-muted-foreground text-sm/none">
                        <span class="font-semibold">
                           {{ formattedDate }}
                        </span>
                    </div>
                </div>
            </div>
            <p class="text-muted-foreground text-sm/relaxed first-letter:uppercase" >
                 {{ medicalCase.title }}
            </p>
            <div class="flex flex-wrap gap-2  text-xs">
                <Badge variant="outline">{{ medicalCase.priority || 'Routine' }}</Badge>
                <Badge variant="outline"> {{ medicalCase.case_type }}</Badge>
                <Badge variant="outline">     {{ medicalCase.status?.toLowerCase().replace('_', ' ') || 'Pending' }}</Badge>
            </div>
            <div class="flex items-center justify-between gap-2">
                <div class="flex flex-row gap-2">
                    <Button variant="outline">
                        <span class="text-sm font-medium">{{t('View')}}</span>
                        <EyeIcon class="h-4 w-4" />
                    </Button>
                    <Button variant="outline">
                        <span class="text-sm font-medium">{{t('Receipt')}}</span>
                        <ReceiptText  class="h-4 w-4" />
                    </Button>
                    <Button variant="outline">
                        <span class="text-sm font-medium">{{t('Pay')}}</span>
                        <HandCoins   class="h-4 w-4" />
                    </Button>
                </div>
                <Button>
                    <span class="text-sm font-medium">{{t('Apply')}}</span>
                    <LucideArrowRight class="h-4 w-4" />
                </Button>
            </div>
        </CardContent>
    </Card>
</template>
