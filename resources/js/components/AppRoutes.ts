import type { NavItem } from '@/types';
import { GraduationCap, LayoutGrid, Users,ListCheck,ListChecks, FileText, DollarSign, CheckSquare } from 'lucide-vue-next';
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
        title: t('My Cases'),
        href: route('my-cases'),
        icon: ListCheck,
        routeName: 'my-cases',
        adminOnly: false,
    },
    {
        title: t('Balance'),
        href: route('balance'),
        icon: DollarSign,
        routeName: 'balance',
        adminOnly: false,
    },
    {
        title: t('Settings'),
        href: route('case-pricing'),
        icon: DollarSign,
        routeName: 'case-pricing',
        adminOnly: true,
    }
];
