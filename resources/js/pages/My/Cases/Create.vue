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
import { Dialog, DialogFooter, DialogHeader, DialogScrollContent, DialogTitle } from '@/components/ui/dialog';

const props = defineProps({
    person: {
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
    case_types: {
        type: Array,
        required: true,
    },
    urgencies: {
        type: Array,
        required: true,
    },
});

const form = useForm<PatientCaseRequestDto>({
    priority: null,
    title: '',
    description: '',
    status: null,
    case_type: null,
    attachments: [],
    uploaded_attachments: [],
});

const storeCase = () => {
    form.post(route('my-cases-store'), {
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>
<template>
    <Dialog :open="openModal" @update:open="close">
        <DialogScrollContent class="max-w-2xl">
            <DialogHeader>
                <DialogTitle>
                    {{ t('New case') }}
                </DialogTitle>
            </DialogHeader>
            <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                    <div>
                        <Label for="title">{{ t('Title') }}</Label>
                        <Input id="title" ref="title" v-model="form.title" :placeholder="t('Title')" name="title" type="text" />
                        <InputError :message="form.errors.title" />
                    </div>

                    <div>
                        <Label for="title">{{ t('Priority') }}</Label>
                        <VSyncSelect
                            :options="props.urgencies"
                            :reduce="(option) => option.key"
                            :get-label="(option) => t(option.value.toString())"
                            id="priority"
                            ref="priority"
                            v-model="form.priority"
                            :placeholder="t('Select priority')"
                            name="priority"
                        />
                        <InputError :message="form.errors.priority" />
                    </div>

                    <div>
                        <Label for="title">{{ t('Type') }}</Label>

                        <VSyncSelect
                            :options="props.case_types"
                            :reduce="(option) => option.key"
                            :get-label="(option) => t(option.value.toString())"
                            id="case_type"
                            ref="case_type"
                            v-model="form.case_type"
                            :placeholder="t('Select case type')"
                            name="case_type"
                        />
                        <InputError :message="form.errors.case_type" />
                    </div>

                    <div class="md:col-span-2 lg:col-span-3">
                        <Label for="description">{{ t('Description') }}</Label>
                        <Textarea
                            rows="6"
                            id="description"
                            ref="description"
                            v-model="form.description"
                            :placeholder="t('Description')"
                            name="description"
                            type="text"
                        />
                        <InputError :message="form.errors.description" />
                    </div>
                    <div class="mr-10 md:col-span-2 lg:col-span-3">
                        <Label for="attachments">{{ t('Attachments') }}</Label>
                        <FileInput
                            class="w-full"
                            id="attachments"
                            ref="attachments"
                            v-model="form.attachments"
                            :placeholder="t('Select attachments')"
                            name="attachments"
                        />
                        <InputError :message="form.errors.attachments" />
                    </div>
                </div>
            </div>
            <DialogFooter>
                <Button variant="outline" size="sm" @click="storeCase">
                    {{ $t('Store') }}
                </Button>
            </DialogFooter>
        </DialogScrollContent>
    </Dialog>
</template>
