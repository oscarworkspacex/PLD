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
import admin from '@/routes/admin';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { LayoutGrid, FileSpreadsheet, FolderOpen, UserRoundPlus, Users, BellRing, ShieldAlert, HandCoins, Gauge } from 'lucide-vue-next';
import { computed } from 'vue';
import AppLogo from './AppLogo.vue';

const page = usePage();
const user = page.props.auth.user;

const allNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
    {
        title: 'Subir excel',
        href: admin.excel.index(),
        icon: FileSpreadsheet,
        permission: 'Excel read',
    },
    {
        title: 'Archivos subidos',
        href: admin.excel.files(),
        icon: FolderOpen,
        permission: 'Excel read',
    },
    {
        title: 'Captura de cliente',
        href: dashboard({ query: { section: 'captura-cliente' } }),
        icon: UserRoundPlus,
    },
    {
        title: 'Seleccionar clientes',
        href: dashboard({ query: { section: 'seleccionar-clientes' } }),
        icon: Users,
    },
    {
        title: 'Prestamo',
        href: dashboard({ query: { section: 'prestamos' } }),
        icon: HandCoins,
    },
    {
        title: 'Umbral',
        href: dashboard({ query: { section: 'umbral' } }),
        icon: Gauge,
    },
    {
        title: 'Usuarios del sistema',
        href: dashboard({ query: { section: 'usuarios-sistema' } }),
        icon: Users,
        permission: 'User read',
    },
    {
        title: 'Alertas',
        href: dashboard({ query: { section: 'alertas' } }),
        icon: BellRing,
    },
    {
        title: 'Clasificacion de riesgo',
        href: dashboard({ query: { section: 'clasificacion-riesgo' } }),
        icon: ShieldAlert,
    },
    {
        title: 'Alertas anonimas',
        href: dashboard({ query: { section: 'alertas-anonimas' } }),
        icon: BellRing,
    },
];

const mainNavItems = computed(() => {
    // Si el usuario tiene el rol 'root', mostrar todos los items (como en Gate::before)
    const isRoot = user?.roles?.includes('root') ?? false;
    
    return allNavItems.filter((item) => {
        // Si el item no tiene permiso requerido, siempre se muestra
        if (!item.permission) {
            return true;
        }
        // Si el usuario es 'root', mostrar todos los items
        if (isRoot) {
            return true;
        }
        // Si tiene permiso requerido, verificar que el usuario lo tenga
        return user?.permissions?.includes(item.permission) ?? false;
    });
});

const footerNavItems: NavItem[] = [
 
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset" class="bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950">
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_20%_20%,rgba(56,189,248,0.15),transparent_40%),radial-gradient(circle_at_80%_0%,rgba(129,140,248,0.16),transparent_35%),radial-gradient(circle_at_60%_70%,rgba(16,185,129,0.12),transparent_30%)]" />
        <SidebarHeader class="relative z-10">
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton
                        size="lg"
                        as-child
                        class="h-auto min-h-[5.5rem] items-start overflow-visible py-3"
                    >
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent class="relative z-10">
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter class="relative z-10">
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
