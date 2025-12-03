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
import { BookOpen, Folder, LayoutGrid, FileSpreadsheet } from 'lucide-vue-next';
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
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
