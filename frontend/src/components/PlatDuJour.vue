<script setup>
import { computed, onMounted, ref } from 'vue'
import { api } from '../api'
import { ariary } from '../utils/format'

const props = defineProps({
  modifiable: { type: Boolean, default: false },
  plats: { type: Array, default: () => [] },
})
const emit = defineEmits(['choisir'])

const platDuJour = ref(null)
const idSelectionne = ref('')
const erreur = ref('')
const enCours = ref(false)

const platsDisponibles = computed(() => props.plats.filter((plat) => plat.disponible))

async function charger() {
  try {
    platDuJour.value = await api.platDuJour.lire()
  } catch (e) {
    erreur.value = e.message
  }
}

async function definir() {
  if (!idSelectionne.value) return

  enCours.value = true
  erreur.value = ''
  try {
    platDuJour.value = await api.platDuJour.definir(idSelectionne.value)
  } catch (e) {
    erreur.value = e.message
  } finally {
    enCours.value = false
  }
}

onMounted(charger)
</script>

<template>
  <section class="plat-du-jour panneau">
    <h2>Plat du jour</h2>

    <p v-if="erreur" class="erreur">{{ erreur }}</p>
    <p v-else-if="!platDuJour">Aucun plat du jour n’a encore été choisi.</p>
    <p v-else-if="modifiable" class="plat-du-jour-nom">
      {{ platDuJour.nom }} — {{ ariary(platDuJour.prix) }}
    </p>

    <template v-if="modifiable">
      <p v-if="!platDuJour">Choisissez un plat disponible pour aujourd’hui.</p>
      <div class="plat-du-jour-choix">
        <select v-model.number="idSelectionne">
          <option value="" disabled>Choisir un plat…</option>
          <option v-for="plat in platsDisponibles" :key="plat.id" :value="plat.id">
            {{ plat.nom }}
          </option>
        </select>
        <button class="bouton" :disabled="!idSelectionne || enCours" @click="definir">
          Choisir
        </button>
      </div>
    </template>

    <button v-else-if="platDuJour" class="plat-du-jour-action" @click="emit('choisir', platDuJour)">
      Ajouter {{ platDuJour.nom }} à la commande
    </button>
  </section>
</template>
