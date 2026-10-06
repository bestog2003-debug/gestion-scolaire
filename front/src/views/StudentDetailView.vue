<!-- Fiche d'un élève (admin) : relevé de notes avec saisie, et gestion des absences. -->
<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { useReportStore } from '../stores/report'
import { useCatalogStore } from '../stores/catalog'
import { useGradesStore } from '../stores/grades'
import { useAbsencesStore } from '../stores/absences'
import StudentReport from '../components/StudentReport.vue'
import GradeForm from '../components/GradeForm.vue'
import AbsenceList from '../components/AbsenceList.vue'

const studentId = Number(useRoute().params.id)
const reportStore = useReportStore()
const catalogStore = useCatalogStore()
const gradesStore = useGradesStore()
const absencesStore = useAbsencesStore()

const isGradeFormOpen = ref(false)
const gradeBeingEdited = ref(null)
const newAbsence = ref({ absent_on: new Date().toISOString().slice(0, 10), justified: false, reason: '' })

onMounted(() => { reportStore.fetchStudentReport(studentId); catalogStore.fetchCatalog() })

function openGradeForm(grade = null) {
  gradeBeingEdited.value = grade
  isGradeFormOpen.value = true
}
async function saveGrade(gradeForm) {
  await gradesStore.saveGrade(gradeForm)
  isGradeFormOpen.value = false
}
async function deleteGrade(grade) {
  if (confirm('Supprimer cette note ?')) await gradesStore.removeGrade(grade.id, studentId)
}
async function addAbsence() {
  await absencesStore.addAbsence({ ...newAbsence.value, student_id: studentId })
  newAbsence.value.reason = ''
}
</script>

<template>
  <div v-if="reportStore.report" class="space-y-6">
    <h1 class="text-xl font-semibold text-board">{{ reportStore.report.student.first_name }} {{ reportStore.report.student.last_name }}</h1>

    <section class="space-y-3">
      <button class="rounded bg-board px-4 py-2 text-paper" @click="openGradeForm()">Saisir une note</button>
      <GradeForm v-if="isGradeFormOpen" :key="gradeBeingEdited?.id" :student-id="studentId" :subjects="catalogStore.subjects" :grade="gradeBeingEdited" @save="saveGrade" @cancel="isGradeFormOpen = false" />
      <StudentReport :report="reportStore.report" editable @edit-grade="openGradeForm" @delete-grade="deleteGrade" />
    </section>

    <section class="space-y-3">
      <h2 class="font-semibold">Absences</h2>
      <div class="flex flex-wrap gap-2 rounded-lg border border-board/15 bg-white p-3">
        <input v-model="newAbsence.absent_on" type="date" class="rounded border p-2" />
        <input v-model="newAbsence.reason" placeholder="Motif" class="min-w-0 flex-1 rounded border p-2" />
        <label class="flex items-center gap-1 text-sm"><input v-model="newAbsence.justified" type="checkbox" /> Justifiée</label>
        <button class="rounded bg-board px-4 py-2 text-paper" @click="addAbsence">Ajouter</button>
      </div>
      <AbsenceList :absences="reportStore.report.absences" editable
        @toggle-justified="(absence) => absencesStore.toggleJustified(absence, studentId)"
        @delete-absence="(absence) => absencesStore.removeAbsence(absence.id, studentId)" />
    </section>
  </div>
</template>
