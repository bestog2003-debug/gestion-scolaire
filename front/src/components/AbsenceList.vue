<!-- Liste des absences d'un élève ; les actions ne sont visibles que si `editable`. -->
<script setup>
defineProps({ absences: Array, editable: Boolean })
const emit = defineEmits(['toggle-justified', 'delete-absence'])
</script>

<template>
  <p v-if="!absences.length" class="text-slate-500">Aucune absence enregistrée.</p>
  <ul v-else class="divide-y rounded-lg border border-board/15 bg-white">
    <li v-for="absence in absences" :key="absence.id" class="flex flex-wrap items-center gap-3 p-3 text-sm">
      <span class="font-medium">{{ absence.absent_on }}</span>
      <span class="rounded px-2 py-0.5" :class="absence.justified ? 'bg-board/10 text-board' : 'bg-alert/10 text-alert'">
        {{ absence.justified ? 'Justifiée' : 'Non justifiée' }}
      </span>
      <span class="text-slate-500">{{ absence.reason }}</span>
      <span v-if="editable" class="ml-auto flex gap-3">
        <button class="text-board underline" @click="emit('toggle-justified', absence)">Changer le statut</button>
        <button class="text-alert underline" @click="emit('delete-absence', absence)">Supprimer</button>
      </span>
    </li>
  </ul>
</template>
