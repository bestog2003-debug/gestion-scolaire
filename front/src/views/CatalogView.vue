<!-- Gestion des classes et des matières (avec coefficient). -->
<script setup>
import { ref, onMounted } from 'vue'
import { useCatalogStore } from '../stores/catalog'

const catalogStore = useCatalogStore()
const newClassName = ref('')
const newSubjectName = ref('')
const newSubjectCoefficient = ref(1)

onMounted(catalogStore.fetchCatalog)

async function addClass() {
  if (!newClassName.value) return
  await catalogStore.addClass(newClassName.value)
  newClassName.value = ''
}
async function addSubject() {
  if (!newSubjectName.value) return
  await catalogStore.addSubject(newSubjectName.value, newSubjectCoefficient.value)
  newSubjectName.value = ''
}
</script>

<template>
  <div class="grid gap-6 md:grid-cols-2">
    <section class="space-y-3">
      <h2 class="font-semibold">Classes</h2>
      <div class="flex gap-2">
        <input v-model="newClassName" placeholder="Nom de la classe" class="min-w-0 flex-1 rounded border bg-white p-2" />
        <button class="rounded bg-board px-4 text-paper" @click="addClass">Ajouter</button>
      </div>
      <ul class="divide-y rounded-lg border border-board/15 bg-white">
        <li v-for="schoolClass in catalogStore.classes" :key="schoolClass.id" class="flex justify-between p-3">
          {{ schoolClass.name }}
          <button class="text-sm text-alert underline" @click="catalogStore.removeClass(schoolClass.id)">Supprimer</button>
        </li>
      </ul>
    </section>
    <section class="space-y-3">
      <h2 class="font-semibold">Matières</h2>
      <div class="flex gap-2">
        <input v-model="newSubjectName" placeholder="Nom de la matière" class="min-w-0 flex-1 rounded border bg-white p-2" />
        <input v-model.number="newSubjectCoefficient" type="number" min="0.5" step="0.5" class="w-20 rounded border bg-white p-2" />
        <button class="rounded bg-board px-4 text-paper" @click="addSubject">Ajouter</button>
      </div>
      <ul class="divide-y rounded-lg border border-board/15 bg-white">
        <li v-for="subject in catalogStore.subjects" :key="subject.id" class="flex justify-between p-3">
          <span>{{ subject.name }} <span class="text-sm text-slate-500">coef. {{ subject.coefficient }}</span></span>
          <button class="text-sm text-alert underline" @click="catalogStore.removeSubject(subject.id)">Supprimer</button>
        </li>
      </ul>
    </section>
  </div>
</template>
