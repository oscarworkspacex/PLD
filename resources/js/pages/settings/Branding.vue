<script setup lang="ts">
import { Head, useForm, usePage } from "@inertiajs/vue3";
import { ref, computed } from "vue";

import HeadingSmall from "@/components/HeadingSmall.vue";
import InputError from "@/components/InputError.vue";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import AppLayout from "@/layouts/AppLayout.vue";
import SettingsLayout from "@/layouts/settings/Layout.vue";
import { type BreadcrumbItem } from "@/types";

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: "Configuración de marca",
        href: "/settings/branding",
    },
];

const page = usePage();
const user = computed(() => page.props.auth?.user);

const form = useForm({
    custom_logo: null as File | null,
    custom_company_name: user.value?.custom_company_name || "",
});

const logoPreview = ref<string | null>(
    user.value?.custom_logo ? `/storage/${user.value.custom_logo}` : null
);

const handleFileChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    const file = target.files?.[0];
    
    if (file) {
        form.custom_logo = file;
        logoPreview.value = URL.createObjectURL(file);
    }
};

const submit = () => {
    form.post("/settings/branding", {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            form.reset("custom_logo");
        },
    });
};

const resetBranding = () => {
    if (confirm("¿Estás seguro de que quieres restaurar el logo y nombre por defecto?")) {
        form.delete("/settings/branding", {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                logoPreview.value = null;
                form.custom_company_name = "";
            },
        });
    }
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Configuración de marca" />

        <SettingsLayout>
            <div class="flex flex-col space-y-6">
                <HeadingSmall
                    title="Personalización de marca"
                    description="Personaliza el logo y nombre de tu empresa (segundo logo)"
                />

                <form @submit.prevent="submit" class="space-y-6">
                    <div class="grid gap-2">
                        <Label for="custom_company_name" class="text-white">Nombre de la empresa</Label>
                        <Input
                            id="custom_company_name"
                            v-model="form.custom_company_name"
                            type="text"
                            class="mt-1 block w-full text-white bg-gray-800 border-gray-700"
                            placeholder="S.A.P.I. de C.V. SOFOM E.N.R"
                        />
                        <InputError class="mt-2" :message="form.errors.custom_company_name" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="custom_logo" class="text-white">Logo personalizado</Label>
                        
                        <div v-if="logoPreview" class="mb-4">
                            <p class="text-sm text-white mb-2">Vista previa:</p>
                            <img 
                                :src="logoPreview" 
                                alt="Vista previa del logo" 
                                class="w-24 h-24 object-cover rounded-md border border-gray-700"
                            />
                        </div>

                        <Input
                            id="custom_logo"
                            type="file"
                            accept="image/*"
                            class="mt-1 block w-full text-white bg-gray-800 border-gray-700 file:text-white file:bg-gray-700"
                            @change="handleFileChange"
                        />
                        <p class="text-sm text-white">
                            Formatos aceptados: JPG, PNG, GIF (máximo 2MB)
                        </p>
                        <InputError class="mt-2" :message="form.errors.custom_logo" />
                    </div>

                    <div class="flex items-center gap-4">
                        <Button
                            type="submit"
                            :disabled="form.processing"
                        >
                            Guardar cambios
                        </Button>

                        <Button
                            type="button"
                            variant="outline"
                            @click="resetBranding"
                            :disabled="form.processing"
                            v-if="user?.custom_logo || user?.custom_company_name"
                        >
                            Restaurar valores por defecto
                        </Button>

                        <Transition
                            enter-active-class="transition ease-in-out"
                            enter-from-class="opacity-0"
                            leave-active-class="transition ease-in-out"
                            leave-to-class="opacity-0"
                        >
                            <p
                                v-show="form.recentlySuccessful"
                                class="text-sm text-green-400 font-semibold"
                            >
                                ✓ Cambios guardados correctamente
                            </p>
                        </Transition>
                    </div>
                </form>

                <div class="mt-8 p-4 bg-gray-800 rounded-lg border border-gray-700">
                    <h3 class="text-sm font-semibold mb-2 text-white">Nota importante:</h3>
                    <p class="text-sm text-white">
                        El primer logo (Intelillaw) permanece fijo y no se puede cambiar. 
                        Solo puedes personalizar el segundo logo y su nombre.
                    </p>
                </div>
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
