<template>
  <div class="bg-app min-h-screen text-white font-rajdhani">
<nav class="fixed top-0 left-0 w-full z-50 border-b border-[#ff0055] shadow-neon backdrop-blur-md bg-black/10">
  <div class="max-w-screen-xl mx-auto py-3 min-h-[64px] relative flex items-center justify-center">

    <!-- 🅰️ Título con margen izquierdo -->
    <div class="absolute left-0 pl-4 text-[#ff0055] text-md font-bold tracking-widest glow-text">
      CORE ASSISTENT
    </div>

    <!-- 🍔 Botón hamburguesa con margen derecho -->
    <button @click="toggleNavigation" class="absolute right-0 pr-4 lg:hidden text-[#00f0ff] focus:outline-none">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1"
           viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round"
              d="M4 6h16M4 12h16M4 18h16"></path>
      </svg>
    </button>

    <!-- 🔗 Enlaces centrados -->
    <ul class="hidden lg:flex space-x-12 text-sm uppercase font-semibold">
      <li><a href="#" class="nav-link">Inicio</a></li>
      <li><a href="#" class="nav-link">Módulos</a></li>
      <li><a href="#" class="nav-link">Red</a></li>
      <li><a href="#" class="nav-link">Logs</a></li>
      <li><a href="#" class="nav-link">Salir</a></li>
    </ul>
  </div>

  <!-- 🔻 Línea inferior decorativa -->
  <div class="h-1 bg-gradient-to-r from-[#ff0055] via-[#00f0ff] to-[#ff0055] animate-pulse"></div>

  <!-- 📱 Menú móvil -->
  <transition name="fade">
    <div v-if="showingNavigationDropdown" class="lg:hidden bg-black/10 backdrop-blur-md px-6 py-4 space-y-3 text-center">
      <a href="#" class="nav-link block">Inicio</a>
      <a href="#" class="nav-link block">Módulos</a>
      <a href="#" class="nav-link block">Red</a>
      <a href="#" class="nav-link block">Logs</a>
      <a href="#" class="nav-link block">Salir</a>
    </div>
  </transition>
</nav>


    <!-- 📄 Contenido -->
    <main class="pt-24 px-6">
      <slot />
    </main>
  </div>
</template>

<script>
export default {
  name: 'AuthenticatedLayout',
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

<style>

.font-rajdhani {
  font-family: 'Rajdhani', sans-serif;
}

.shadow-neon {
  box-shadow: 0 0 10px #ff0055, 0 0 20px #00f0ff;
}

.glow-text {
  text-shadow: 0 0 5px #ff0055, 0 0 10px #ff0055;
}

.nav-link {
  color: #00f0ff;
  position: relative;
  transition: color 0.3s ease;
}

.nav-link::after {
  content: '';
  position: absolute;
  bottom: -4px;
  left: 0;
  width: 100%;
  height: 2px;
  background: #00f0ff !important;
  transform: scaleX(0);
  transform-origin: left;
  transition: transform 0.3s ease;
}

.nav-link:hover {
  color: #ff0055;
}

.nav-link:hover::after {
  transform: scaleX(1);
}

.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style>

