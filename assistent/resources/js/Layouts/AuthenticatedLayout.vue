<template>
  <div>
    <div class="min-h-screen background-app">
   

      <!-- Page Content -->
      <main>
        <slot />
      </main>
    </div>
  </div>
</template>

<script>
import ApplicationLogo from '@/Components/ApplicationLogo.vue'
import Dropdown from '@/Components/Dropdown.vue'
import DropdownLink from '@/Components/DropdownLink.vue'
import NavLink from '@/Components/NavLink.vue'
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue'
import { Link } from '@inertiajs/vue3'

export default {
  name: 'AuthenticatedLayout',
  components: {
    ApplicationLogo,
    Dropdown,
    DropdownLink,
    NavLink,
    ResponsiveNavLink,
    Link,
  },
  data() {
    return {
      showingNavigationDropdown: false,
    }
  },
  methods: {
    toggleNavigation() {
      this.showingNavigationDropdown = !this.showingNavigationDropdown
    },
  },
}
</script>
