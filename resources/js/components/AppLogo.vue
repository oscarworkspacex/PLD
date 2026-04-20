<script setup lang="ts">
</script>

<template>
    <div class="flex items-center pt-2">
        <!-- Logos en columna -->
        <div class="flex flex-col gap-3">
            <!-- Logo Intelillaw arriba -->
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
            
            <!-- Logo Liquidez abajo -->
            <div class="flex items-center gap-3">
                <div
                    class="flex aspect-square size-8 items-center justify-center rounded-md overflow-hidden"
                >
                    <img 
                        src="/images/ImgLiquidez.jpg" 
                        alt="Liquidez Logo" 
                        class="size-full object-cover"
                    />
                </div>
                <span class="text-xs font-medium text-muted-foreground leading-tight">S.A.P.I. de C.V. SOFOM E.N.R</span>
            </div>
        </div>
    </div>
</template>
