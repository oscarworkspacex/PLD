<script setup lang="ts">
import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";

const page = usePage();
const user = computed(() => page.props.auth?.user);

const customLogo = computed(() => {
    return user.value?.custom_logo 
        ? `/storage/${user.value.custom_logo}` 
        : "/images/ImgLiquidez.jpg";
});

const customCompanyName = computed(() => {
    return user.value?.custom_company_name || "S.A.P.I. de C.V. SOFOM E.N.R";
});
</script>

<template>
    <div class="flex items-center pt-2">
        <div class="flex flex-col gap-3">
            <!-- Logo Intelillaw arriba (FIJO) -->
            <div class="flex items-center gap-3">
                <div
                    class="flex aspect-square size-8 items-center justify-center rounded-md overflow-hidden"
                >
                    <img 
                        src="/images/intelillaw-logo.jpeg" 
                        alt="Intelillaw Logo" 
                        class="size-full object-cover"
                    />
                </div>
                <span class="text-xs font-semibold leading-tight">Intelillaw</span>
            </div>
            
            <!-- Logo Personalizable (usuario puede cambiar) -->
            <div class="flex items-center gap-3">
                <div
                    class="flex aspect-square size-8 items-center justify-center rounded-md overflow-hidden"
                >
                    <img 
                        :src="customLogo" 
                        :alt="customCompanyName" 
                        class="size-full object-cover"
                    />
                </div>
                <span class="text-xs font-medium text-muted-foreground leading-tight">{{ customCompanyName }}</span>
            </div>
        </div>
    </div>
</template>
