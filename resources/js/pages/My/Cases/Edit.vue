<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { PatientCaseRequestDto } from '@/types/generated';
import { t } from '@/lib/utils';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import InputError from '@/components/InputError.vue';
import { Textarea } from '@/components/ui/textarea';
import VSyncSelect from '@/components/VSyncSelect.vue';
import FileInput from '@/components/FileUpload/FileInput.vue';
import UploadedPreview from '@/components/FileUpload/UploadedPreview.vue';
import { Dialog, DialogFooter, DialogHeader, DialogScrollContent, DialogTitle } from '@/components/ui/dialog';

const props = defineProps({
    person: {
        type: Object,
        required: true,
    },
    case: {
        type: Object, // The existing case data
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
    case_types: {
        type: Array,
        required: true,
    },
    urgencies: {
        type: Array,
        required: true,
    },
});

/**
 * Explicit Form Initialization
 * We map the props specifically to the DTO structure.
 */
const form = useForm<PatientCaseRequestDto>({
    title: props.case.title ?? '',
    priority: props.case.priority ?? null,
    case_type: props.case.case_type ?? null,
    description: props.case.description ?? '',
    status: props.case.status ?? null,
    attachments: [], // New files to be uploaded
    uploaded_attachments: props.case.uploaded_attachments ?? [], // Existing files
});

const updateCase = () => {
    form.post(route('my-cases-update', { case: props.case.id }), {
        onSuccess: () => {
            props.close();
        },
        preserveScroll: true,
    });
};
</script>

<template>
    <Dialog :open="openModal" @update:open="close">
        <DialogScrollContent class="max-w-2xl">
            <DialogHeader>
                <DialogTitle>
                    {{ t('Edit case') }}: {{ props.case.case_code }}
                </DialogTitle>
            </DialogHeader>

            <div class="flex h-full flex-1 flex-col gap-6 p-4">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">

                    <!-- Title -->
                    <div class="space-y-2">
                        <Label for="title">{{ t('Title') }}</Label>
                        <Input
                            id="title"
                            v-model="form.title"
                            :placeholder="t('Title')"
                            type="text"
                        />
                        <InputError :message="form.errors.title" />
                    </div>

                    <!-- Priority -->
                    <div class="space-y-2">
                        <Label for="priority">{{ t('Priority') }}</Label>
                        <VSyncSelect
                            id="priority"
                            v-model="form.priority"
                            :options="props.urgencies"
                            :reduce="(option) => option.key"
                            :get-label="(option) => t(option.value.toString())"
                            :placeholder="t('Select priority')"
                        />
                        <InputError :message="form.errors.priority" />
                    </div>

                    <!-- Type -->
                    <div class="space-y-2">
                        <Label for="case_type">{{ t('Type') }}</Label>
                        <VSyncSelect
                            id="case_type"
                            v-model="form.case_type"
                            :options="props.case_types"
                            :reduce="(option) => option.key"
                            :get-label="(option) => t(option.value.toString())"
                            :placeholder="t('Select case type')"
                        />
                        <InputError :message="form.errors.case_type" />
                    </div>

                    <!-- Description -->
                    <div class="space-y-2 md:col-span-2 lg:col-span-3">
                        <Label for="description">{{ t('Description') }}</Label>
                        <Textarea
                            id="description"
                            v-model="form.description"
                            rows="6"
                            :placeholder="t('Description')"
                        />
                        <InputError :message="form.errors.description" />
                    </div>

                    <!-- Attachments Section -->
                    <div class="space-y-4 md:col-span-2 lg:col-span-3">
                        <div class="space-y-2">
                            <Label for="attachments">{{ t('New Attachments') }}</Label>
                            <FileInput
                                id="attachments"
                                v-model="form.attachments"
                                class="w-full"
                                :placeholder="t('Select attachments')"
                            />
                            <InputError :message="form.errors.attachments" />
                        </div>

                        <!-- Explicitly show currently uploaded files -->
                        <div v-if="props.case.uploaded_attachments?.length" class="rounded-lg border border-dashed p-4">
                            <Label class="mb-2 block text-xs font-semibold uppercase tracking-wider text-muted-foreground">
                                {{ t('Current Files') }}
                            </Label>
                            <UploadedPreview :file="props.case.uploaded_attachments" />
                        </div>
                    </div>
                </div>
            </div>

            <DialogFooter class="gap-2 sm:gap-0">
                <Button variant="ghost" @click="close">
                    {{ t('Cancel') }}
                </Button>
                <Button
                    variant="outline"
                    @click="updateCase"
                    :disabled="form.processing"
                >
                    <span v-if="form.processing">{{ t('Updating...') }}</span>
                    <span v-else>{{ t('Update Case') }}</span>
                </Button>
            </DialogFooter>
        </DialogScrollContent>
    </Dialog>
</template>
