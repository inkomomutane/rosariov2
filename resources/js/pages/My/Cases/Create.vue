<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import ContainerBox from '@/components/Box/ContainerBox.vue';
import { Button } from '@/components/ui/button';
import { PatientCaseRequestDto } from '@/types/generated';
import { t } from '@/lib/utils';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import InputError from '@/components/InputError.vue';
import { Textarea } from '@/components/ui/textarea';
import VSyncSelect from '@/components/VSyncSelect.vue';
import FileInput from '@/components/FileUpload/FileInput.vue';

const props = defineProps({
    person: {
        type: Object,
        required: true
    },
     case_types: {
        type: Array,
        required: true
     },
    urgencies: {
        type: Array,
        required: true
     }

});

const form = useForm<PatientCaseRequestDto>({
    priority: null,
    title: '',
    description: '',
    status: null,
    case_type: null,
    attachments: [],
    uploaded_attachments: []
})


const storeCase  = () => {
    form.post(route('my-cases-store'), {
        onSuccess: () => {
            form.reset();
        }
    })
}


</script>
<template>
    <Head :title="$t('Create case')" />
    <AppLayout>
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <ContainerBox>
                 <template #header-left>
                        <h2 class="text-lg font-medium">
                            {{ $t('Create case for: ') }}  {{ props.person.name }}
                        </h2>
                 </template>



                 <template #header-right>
                      <Button variant="outline" size="sm" @click="storeCase">
                        {{ $t('Store') }}
                      </Button>
                 </template>
                 <template #content-table>

                     <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                         <div>
                             <Label for="title">{{ t('Title') }}</Label>
                             <Input id="title" ref="title" v-model="form.title" :placeholder="t('Title')" name="title" type="text" />
                             <InputError :message="form.errors.title" />
                         </div>

                         <div>
                             <Label for="title">{{ t('Priority') }}</Label>
                             <VSyncSelect :options="props.urgencies"
                                          :reduce="(option) => option.key"
                                          :get-label="(option) => t(option.value.toString())"
                                          id="priority" ref="priority"
                                          v-model="form.priority"
                                          :placeholder="t('Select priority')"
                                          name="priority"
                             />
                             <InputError :message="form.errors.priority" />
                         </div>

                         <div>
                             <Label for="title">{{ t('Type') }}</Label>

                                <VSyncSelect :options="props.case_types"
                                            :reduce="(option) => option.key"
                                            :get-label="(option) => t(option.value.toString())"
                                            id="case_type" ref="case_type"
                                            v-model="form.case_type"
                                            :placeholder="t('Select case type')"
                                            name="case_type"
                                />
                             <InputError :message="form.errors.case_type" />
                         </div>


                         <div class=" md:col-span-2 lg:col-span-3">
                                <Label for="description">{{ t('Description') }}</Label>
                                <Textarea rows="6" id="description" ref="description" v-model="form.description" :placeholder="t('Description')" name="description" type="text" />
                                <InputError :message="form.errors.description" />
                         </div>
                         <div class=" md:col-span-2 lg:col-span-3 mr-10">
                             <Label for="attachments">{{ t('Attachments') }}</Label>
                             <FileInput
                                 class="w-full"
                                 id="attachments"
                                 ref="attachments"
                                 v-model="form.attachments"
                                 :placeholder="t('Select attachments')" name="attachments"
                             />
                             <InputError :message="form.errors.attachments" />
                         </div>
                     </div>


                 </template>
            </ContainerBox>
        </div>
    </AppLayout>
</template>
