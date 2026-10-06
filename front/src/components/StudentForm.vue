<!-- Formulaire d'ajout / modification d'un élève (l'email et le mot de passe ne servent qu'à la création). -->
<script setup>
import { reactive } from 'vue'

const props = defineProps({ student: Object, classes: Array })
const emit = defineEmits(['save', 'cancel'])

const studentForm = reactive({
  id: props.student?.id,
  first_name: props.student?.first_name ?? '',
  last_name: props.student?.last_name ?? '',
  class_id: props.student?.class_id ?? '',
  email: '',
  password: '',
})
</script>

<template>
  <div class="grid gap-3 rounded-lg border border-board/15 bg-white p-4 sm:grid-cols-2">
    <input v-model="studentForm.first_name" placeholder="Prénom" class="rounded border p-2" />
    <input v-model="studentForm.last_name" placeholder="Nom" class="rounded border p-2" />
    <select v-model="studentForm.class_id" class="rounded border p-2">
      <option value="">Sans classe</option>
      <option v-for="schoolClass in classes" :key="schoolClass.id" :value="schoolClass.id">{{ schoolClass.name }}</option>
    </select>
    <template v-if="!student">
      <input v-model="studentForm.email" type="email" placeholder="Email de connexion" class="rounded border p-2" />
      <input v-model="studentForm.password" type="password" placeholder="Mot de passe initial" class="rounded border p-2" />
    </template>
    <div class="flex gap-2 sm:col-span-2">
      <button class="rounded bg-board px-4 py-2 text-paper" @click="emit('save', { ...studentForm })">Enregistrer</button>
      <button class="rounded border px-4 py-2" @click="emit('cancel')">Annuler</button>
    </div>
  </div>
</template>
