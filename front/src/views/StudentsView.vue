<!-- Liste des élèves : recherche, ajout, modification, suppression. -->
<script setup>
import { ref, onMounted, watch } from 'vue'
import { useStudentsStore } from '../stores/students'
import { useCatalogStore } from '../stores/catalog'
import StudentForm from '../components/StudentForm.vue'

const studentsStore = useStudentsStore()
const catalogStore = useCatalogStore()
const searchTerm = ref('')
const studentBeingEdited = ref(null)
const isFormOpen = ref(false)
const errorMessage = ref('')

onMounted(() => { studentsStore.fetchStudents(); catalogStore.fetchCatalog() })
watch(searchTerm, (term) => studentsStore.fetchStudents(term))

function openForm(student = null) {
  studentBeingEdited.value = student
  isFormOpen.value = true
}
async function saveStudent(studentForm) {
  try {
    await studentsStore.saveStudent(studentForm)
    isFormOpen.value = false
    errorMessage.value = ''
    await studentsStore.fetchStudents(searchTerm.value)
  } catch (error) {
    errorMessage.value = error.message
  }
}
async function deleteStudent(student) {
  if (!confirm(`Supprimer ${student.first_name} ${student.last_name} ?`)) return
  await studentsStore.removeStudent(student.id)
  await studentsStore.fetchStudents(searchTerm.value)
}
</script>

<template>
  <div class="space-y-4">
    <div class="flex gap-2">
      <input v-model="searchTerm" placeholder="Rechercher un élève" class="flex-1 rounded border bg-white p-2" />
      <button class="rounded bg-board px-4 text-paper" @click="openForm()">Ajouter</button>
    </div>
    <p v-if="errorMessage" class="text-alert">{{ errorMessage }}</p>
    <StudentForm v-if="isFormOpen" :key="studentBeingEdited?.id" :student="studentBeingEdited" :classes="catalogStore.classes" @save="saveStudent" @cancel="isFormOpen = false" />
    <ul class="divide-y rounded-lg border border-board/15 bg-white">
      <li v-for="student in studentsStore.students" :key="student.id" class="flex flex-wrap items-center gap-3 p-3">
        <router-link :to="`/admin/eleves/${student.id}`" class="font-medium underline">{{ student.first_name }} {{ student.last_name }}</router-link>
        <span class="text-sm text-slate-500">{{ student.class_name ?? 'Sans classe' }}</span>
        <span class="ml-auto flex gap-3 text-sm">
          <button class="text-board underline" @click="openForm(student)">Modifier</button>
          <button class="text-alert underline" @click="deleteStudent(student)">Supprimer</button>
        </span>
      </li>
    </ul>
  </div>
</template>
