<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import { type AppPageProps, type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { BookOpen, CreditCard, Folder, LayoutGrid, MapPinned, Paintbrush, Smartphone, TrendingUp, UserRound } from 'lucide-vue-next';
import { computed } from 'vue';
import AppLogo from './AppLogo.vue';

type SidebarNavItem = NavItem & {
    key: string;
};

const page = usePage<AppPageProps>();
const roles = page.props.auth?.roles ?? [];
const hiddenNavItems = computed(
    () => new Set(page.props.role_ui?.hidden_nav_items ?? []),
);
const canAccessTitanGo = roles.some((role) => ['owner', 'admin', 'super_admin'].includes(role));
const canAccessGroundZero = roles.some((role) => ['owner', 'admin', 'dispatcher', 'bookkeeper'].includes(role));
const canAccessZeroPay = roles.some((role) => ['owner', 'admin', 'bookkeeper'].includes(role));
const canAccessTitanStudio = roles.some((role) => ['owner', 'admin'].includes(role));
const canAccessZeroFuss = roles.includes('customer');
const canAccessTitanNexus = roles.some((role) => ['owner', 'admin'].includes(role));
const canAccessTitanSolo = roles.includes('owner') && ['starter', 'solo', 'single_operator'].includes(page.props.plan?.current ?? '');

const mainNavItems: SidebarNavItem[] = [
    {
        key: 'Dashboard',
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
    ...(canAccessTitanGo
        ? [
              {
                  key: 'TitanGo Panel',
                  title: 'TitanGo Panel',
                  href: '/titango',
                  icon: Smartphone,
              },
          ]
        : []),
    ...(canAccessGroundZero
        ? [
              {
                  key: 'GroundZero Panel',
                  title: 'GroundZero Panel',
                  href: '/groundzero',
                  icon: MapPinned,
              },
          ]
        : []),
    ...(canAccessZeroPay
        ? [
              {
                  key: 'ZeroPay Panel',
                  title: 'ZeroPay Panel',
                  href: '/zeropay',
                  icon: CreditCard,
              },
          ]
        : []),
    ...(canAccessTitanStudio
        ? [
              {
                  key: 'TitanStudio Panel',
                  title: 'TitanStudio Panel',
                  href: '/titanstudio',
                  icon: Paintbrush,
              },
          ]
        : []),
    ...(canAccessZeroFuss
        ? [
              {
                  key: 'ZeroFuss Portal',
                  title: 'ZeroFuss Portal',
                  href: '/zerofuss',
                  icon: Smartphone,
              },
          ]
        : []),
    ...(canAccessTitanNexus
        ? [
              {
                  key: 'TitanNexus Panel',
                  title: 'TitanNexus Panel',
                  href: '/titannexus',
                  icon: TrendingUp,
              },
          ]
        : []),
    ...(canAccessTitanSolo
        ? [
              {
                  key: 'TitanSolo Panel',
                  title: 'TitanSolo Panel',
                  href: '/titansolo',
                  icon: UserRound,
              },
          ]
        : []),
];

const footerNavItems: SidebarNavItem[] = [
    {
        key: 'Github Repo',
        title: 'Github Repo',
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: Folder,
    },
    {
        key: 'Documentation',
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#vue',
        icon: BookOpen,
    },
];

const filterNavItems = (items: SidebarNavItem[]) =>
    items.filter((item) => !hiddenNavItems.value.has(item.key));

const visibleMainNavItems = computed(() => filterNavItems(mainNavItems));
const visibleFooterNavItems = computed(() => filterNavItems(footerNavItems));
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="visibleMainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="visibleFooterNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
