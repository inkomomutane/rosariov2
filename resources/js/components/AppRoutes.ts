import type { NavItem } from '@/types';
import { GraduationCap, LayoutGrid, Users,ListCheck,ListChecks } from 'lucide-vue-next';
import {t} from "@/lib/utils"

export const AppRoutes: NavItem[]  = [
    {
        title: t('Dashboard'),
        href: route('dashboard'),
        icon: LayoutGrid,
        routeName: 'dashboard',
        adminOnly: false,
    },
    {
        title: t('Details'),
        href: route('dashboard.user-person-edit'),
        icon: LayoutGrid,
        routeName: 'dashboard.user-person-edit',
        adminOnly: false,
    },
    {
        title: t('Parient Cases'),
        href: route('all-patient-cases'),
        icon: LayoutGrid,
        routeName: 'all-patient-cases',
        adminOnly: false,
    },

    //
];
