<script setup lang="ts">
import BaseContent from '@/layouts/BaseContent.vue';
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { KeyValueDto, MaritalStatus, PatentData, Sex, UserDto } from '@/types/generated';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import InputError from '@/components/InputError.vue';
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectLabel,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select'

const props = defineProps<{
    user : UserDto,
}>();


const form = useForm<UserDto>(props.user);

const updateProfile = () => {

};


</script>

<template>
    <Head :title="$t('Profile')" />
    <AppLayout>
        <BaseContent :title="$t('Profile')">
            <Card class="rounded-sm shadow-none">
                <CardHeader class="flex flex-col items-center justify-end space-y-3 p-4 md:flex-row md:space-x-4 md:space-y-0">
                    <div class="flex flex-col items-center justify-end   md:flex-row w-full ">
                        <Button @click="updateProfile" class="w-full md:w-auto">
                            {{$t('Update Profile')}}
                        </Button>
                    </div>
                </CardHeader>
                <CardContent class="grid sm:grid-cols-2 md:grid-cols-3 gap-4 ">
                    <div class="grid w-full max-w-sm items-center gap-1.5">
                        <Label for="name" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{$t('Name')}}
                        </Label>
                        <Input
                            id="name"
                            type="text"
                            v-model="form.name"
                            class="w-full"
                            :placeholder="$t('Enter your name')"
                        />
                        <InputError :message="form.errors.name" class="mt-2" />
                    </div>

                </CardContent>
            </Card>
        </BaseContent>
    </AppLayout>
</template>
