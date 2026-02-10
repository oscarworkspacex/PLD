<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'

const props = defineProps<{
  class?: HTMLAttributes['class']
}>()
</script>

<template>
  <main
    data-slot="sidebar-inset"
    :class="cn(
      'relative flex w-full flex-1 flex-col overflow-hidden bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950',
      'md:peer-data-[variant=inset]:m-0 md:peer-data-[variant=inset]:rounded-none md:peer-data-[variant=inset]:shadow-none md:peer-data-[variant=inset]:peer-data-[state=collapsed]:ml-0',
      props.class,
    )"
  >
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_20%_20%,rgba(56,189,248,0.15),transparent_40%),radial-gradient(circle_at_80%_0%,rgba(129,140,248,0.16),transparent_35%),radial-gradient(circle_at_60%_70%,rgba(16,185,129,0.12),transparent_30%)]" />
    <div class="relative z-10 flex h-full w-full flex-col">
      <slot />
    </div>
  </main>
</template>
