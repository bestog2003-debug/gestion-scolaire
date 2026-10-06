<!-- Carte de statistique : un libellé et une valeur, en rouge si `isAlert`. -->
<script setup>
defineProps({ label: String, value: [String, Number], isAlert: Boolean })
</script>

<template>
  <div class="rounded-lg border border-board/15 bg-white p-4">
    <p class="text-sm text-slate-500">{{ label }}</p>
    <p class="text-2xl font-semibold" :class="isAlert ? 'text-alert' : 'text-board'">{{ value ?? '–' }}</p>
  </div>
</template>
