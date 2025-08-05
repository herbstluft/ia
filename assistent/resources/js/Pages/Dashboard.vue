<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <div class="p-6">

      <!-- Asistente holográfico -->
      <HologramAssistant />
      
    </div>
  </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'
import { useSpeech } from '@/Composables/useSpeech'
import HologramAssistant from '@/Components/HologramAssistant.vue'

export default {
  name: 'Dashboard',
  components: {
    AuthenticatedLayout,
    Head,
    HologramAssistant,
  },
  data() {
    return {
    }
  },
  methods: {

  },
}
</script>
