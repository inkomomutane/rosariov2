<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { InfiniteScroll } from "@inertiajs/vue3";
import { t } from '@/lib/utils';
import { EllipsisVertical  } from 'lucide-vue-next'
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar'
import { Button } from '@/components/ui/button'
import {
    Item,
    ItemActions,
    ItemContent,
    ItemDescription,
    ItemMedia,
    ItemTitle,
} from '@/components/ui/item'
import { getInitials } from '@/composables/useInitials';
import { FullPatientCaseDto } from '@/types/generated';
import { Card, CardContent, CardHeader, CardTitle, CardFooter } from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import { Separator } from '@/components/ui/separator'
import { Image, Video, FileText, MoreHorizontal, ThumbsUp, MessageSquare, Share2, Send } from 'lucide-vue-next'
import AsideDashboard from '@/pages/AsideDashboard.vue';
import CaseShortComponent from '@/pages/CaseShortComponent.vue';
const props = defineProps({
    cases: Object,
});

const breadcrumbs = [
    {
        title: t('Dashboard'),
        href: '/dashboard',
    },
];
</script>

<template>
    <Head :title="$t('Dashboard')" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="min-h-screen  pt-4 px-2 md:px-4">
            <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-12 gap-5">
                <AsideDashboard />
                <main class="col-span-1 md:col-span-6 space-y-4">
                    <CaseShortComponent />
                </main>
                <aside class="hidden md:block md:col-span-3 space-y-3">
                </aside>

            </div>
        </div>
    </AppLayout>
</template>

