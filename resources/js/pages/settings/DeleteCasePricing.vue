<script lang="ts" setup>
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogFooter,
    DialogHeader,
    DialogScrollContent,
    DialogTitle,
    DialogDescription
} from '@/components/ui/dialog';
import { t } from '@/lib/utils';
import { router } from '@inertiajs/vue3';
import { CasePricingDto } from '@/types/generated';
import { AlertTriangle, Loader2 } from 'lucide-vue-next';
import { ref } from 'vue';

const props = defineProps({
    close: {
        type: Function,
        required: true,
    },
    openModal: {
        type: Boolean,
        required: true,
    },
    pricing: {
        type: Object as () => CasePricingDto | null,
        default: null,
    }
});

const isProcessing = ref(false);

const confirmDelete = () => {
    if (!props.pricing?.id) return;

    isProcessing.ref = true;
    router.delete(route('case-pricings.destroy', { pricing: props.pricing.id }), {
        preserveScroll: true,
        onFinish: () => {
            isProcessing.value = false;
        },
        onSuccess: () => {
            props.close();
        },
    });
};
</script>

<template>
    <Dialog :open="openModal" @update:open="close">
        <DialogScrollContent class="max-w-md">
            <DialogHeader>
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-100">
                    <AlertTriangle class="h-6 w-6 text-red-600" />
                </div>
                <DialogTitle class="text-center text-xl">
                    {{ t('Delete Pricing') }}
                </DialogTitle>
                <DialogDescription class="text-center">
                    {{ t('Are you sure you want to delete the pricing for') }}
                    <span class="font-bold text-foreground">{{ props.pricing?.case_type }}</span>?
                    {{ t('This action cannot be undone.') }}
                </DialogDescription>
            </DialogHeader>

            <DialogFooter class="sm:justify-center gap-2 mt-4">
                <Button variant="ghost" @click="close" :disabled="isProcessing">
                    {{ t('Cancel') }}
                </Button>
                <Button variant="destructive" @click="confirmDelete" :disabled="isProcessing">
                    <Loader2 v-if="isProcessing" class="mr-2 h-4 w-4 animate-spin" />
                    {{ t('Delete') }}
                </Button>
            </DialogFooter>
        </DialogScrollContent>
    </Dialog>
</template>
