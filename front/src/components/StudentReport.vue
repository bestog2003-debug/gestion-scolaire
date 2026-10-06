<!-- Relevé de notes : moyenne générale puis une carte par matière (modifiable si `editable`). -->
<script setup>
defineProps({ report: Object, editable: Boolean })
const emit = defineEmits(['edit-grade', 'delete-grade'])

const averageColor = (average) => (average !== null && average < 10 ? 'text-alert' : 'text-board')
</script>

<template>
  <section class="space-y-4">
    <div class="rounded-lg bg-board p-4 text-paper">
      <p class="text-sm opacity-80">Moyenne générale</p>
      <p class="text-3xl font-semibold">{{ report.generalAverage ?? '–' }} <span class="text-base font-normal">/ 20</span></p>
    </div>
    <p v-if="!report.subjects.length" class="text-slate-500">Aucune note pour le moment.</p>
    <article v-for="subject in report.subjects" :key="subject.id" class="rounded-lg border border-board/15 bg-white p-4">
      <header class="flex items-baseline justify-between">
        <h3 class="font-semibold">{{ subject.name }} <span class="text-sm font-normal text-slate-500">coef. {{ subject.coefficient }}</span></h3>
        <p class="text-lg font-semibold" :class="averageColor(subject.average)">{{ subject.average }}</p>
      </header>
      <ul class="mt-2 divide-y">
        <li v-for="grade in subject.grades" :key="grade.id" class="flex items-center gap-3 py-1 text-sm">
          <span class="w-16 font-medium">{{ grade.value }} / 20</span>
          <span class="text-slate-500">coef. {{ grade.coefficient }} · {{ grade.graded_on }}</span>
          <span v-if="editable" class="ml-auto flex gap-3">
            <button class="text-board underline" @click="emit('edit-grade', grade)">Modifier</button>
            <button class="text-alert underline" @click="emit('delete-grade', grade)">Supprimer</button>
          </span>
        </li>
      </ul>
    </article>
  </section>
</template>
