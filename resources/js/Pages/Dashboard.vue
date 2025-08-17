<template>
  <Head title="Dashboard" />
  <AuthenticatedLayout>

    <JarvisAssistant />

  </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'
import JarvisAssistant from '@/Components/JarvisAssistant.vue'

export default {
  name: 'Dashboard',
  components: {
    AuthenticatedLayout,
    Head,
    JarvisAssistant,
  },
  methods:{
  },
}
</script>
