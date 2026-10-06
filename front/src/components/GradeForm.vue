<!-- Formulaire de saisie / modification d'une note. -->
<script setup>
import { reactive } from 'vue'

const props = defineProps({ studentId: Number, subjects: Array, grade: Object })
const emit = defineEmits(['save', 'cancel'])

const gradeForm = reactive({
  id: props.grade?.id,
  student_id: props.studentId,
  subject_id: props.grade?.subject_id ?? '',
  value: props.grade?.value ?? '',
  coefficient: props.grade?.coefficient ?? 1,
  graded_on: props.grade?.graded_on ?? new Date().toISOString().slice(0, 10),
})
</script>

<template>
  <div class="grid gap-3 rounded-lg border border-board/15 bg-white p-4 sm:grid-cols-4">
    <select v-model="gradeForm.subject_id" class="rounded border p-2">
      <option value="" disabled>Matière</option>
      <option v-for="subject in subjects" :key="subject.id" :value="subject.id">{{ subject.name }}</option>
    </select>
    <input v-model.number="gradeForm.value" type="number" min="0" max="20" step="0.25" placeholder="Note /20" class="rounded border p-2" />
    <input v-model.number="gradeForm.coefficient" type="number" min="0.5" step="0.5" placeholder="Coef." class="rounded border p-2" />
    <input v-model="gradeForm.graded_on" type="date" class="rounded border p-2" />
    <div class="flex gap-2 sm:col-span-4">
      <button class="rounded bg-board px-4 py-2 text-paper" @click="emit('save', { ...gradeForm })">Enregistrer la note</button>
      <button class="rounded border px-4 py-2" @click="emit('cancel')">Annuler</button>
    </div>
  </div>
</template>
