<template>
  <div class="jarvis">
    <!-- 🔘 Orb interactivo -->
    <div class="smart-orb" :class="{ active: isSpeaking }" @click="startListening">
      <svg viewBox="0 0 200 200" class="orb-svg">
        <defs>
          <radialGradient id="orbGradient" cx="50%" cy="50%" r="50%">
            <stop offset="0%" stop-color="#00aaff" stop-opacity="1" />
            <stop offset="100%" stop-color="#001f33" stop-opacity="0.2" />
          </radialGradient>
        </defs>
        <circle cx="100" cy="100" r="80" fill="url(#orbGradient)" />
        <circle class="pulse" cx="100" cy="100" r="80" />
      </svg>

      <!-- 🌊 Ondas animadas -->
      <div class="wave-container">
        <svg viewBox="0 0 600 200" class="wave" v-if="isSpeaking" >
          <path class="wave-path" d="M0,100 C150,50 450,150 600,100" />
        </svg>
      </div>



      <!-- ✨ Partículas flotantes -->
      <div class="particles">
        <span v-for="n in 12" :key="n" class="particle"></span>
      </div>
    </div>


<!-- Botones -->
<div class="flex flex-wrap gap-4 justify-center p-4">
  <button class="cb-button">
    <span>Enviar</span>
  </button>

  <button class="cb-button reverse">
    <span>Cancelar</span>
  </button>
</div>

<!-- Tabs -->
<div class="cb-tab-group">
  <div class="cb-tab active">
    <div>Inicio</div>
  </div>
  <div class="cb-tab">
    <div>Perfil</div>
  </div>
  <div class="cb-tab">
    <div>Configuración</div>
  </div>
</div>

  </div>
</template>


<script>
export default {
  name: 'JarvisAssistant',
  data() {
    return {
      isSpeaking: false
    }
  },
  methods: {
    startListening() {

        if(responsiveVoice.isPlaying()) {
            responsiveVoice.pause();
            this.isSpeaking = false;
            return;
        }

      if (!('webkitSpeechRecognition' in window)) {
        alert('Tu navegador no soporta reconocimiento de voz.')
        return
      }

      const recognition = new window.webkitSpeechRecognition()
      recognition.lang = 'es-MX'
      recognition.interimResults = false


        recognition.onresult = (event) => {
        const userText = event.results[0][0].transcript.trim().toLowerCase()

        // 🧠 Comando local: "continua"
        if (userText === 'continua' || userText === 'continúa') {
            if (typeof responsiveVoice !== 'undefined') {
            responsiveVoice.resume()
            this.isSpeaking = true
            }
            return
        }

        // Si no es comando local, envía al backend
        this.sendToGemini(userText)
        }


      recognition.onerror = (e) => {
        console.error('🎤 Error de micrófono:', e)
      }

      recognition.start()
    },

    async sendToGemini(text) {
      const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content')

      try {
        const response = await fetch('/jarvis/query', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': token
          },
          body: JSON.stringify({ text })
        })


        const data = await response.json()



        if (data?.error?.code === 429) {
            this.speak("Lo siento, he alcanzado el límite de uso por hoyljdskldjksjdkl fjkljdklfjklds fj ldsjfdkjgk jfkdgk fdjhk gfkhbflvcjblkncfklbjkljcblkjgklbknl gf  jghk jhklf dklhjdklfh j.")
            return
        }

        const reply = data.candidates?.[0]?.content?.parts?.[0]?.text || 'Sin respuesta'
        
        this.speak(reply)
      } catch (error) {
        console.error('❌ Error con Laravel proxy:', error)
      }
    },

    speak(text) {
      if (typeof responsiveVoice === 'undefined') {
        console.warn('❌ responsiveVoice no está disponible.')
        return
      }

      const cleanText = String(text)
        .replace(/\n/g, ' ')
        .replace(/["']/g, '')
        .replace(/\s+/g, ' ')
        .trim()

      if (!cleanText || cleanText.length < 2) {
        console.warn('⚠️ Texto vacío o inválido para síntesis de voz.')
        return
      }

      this.isSpeaking = true

      responsiveVoice.speak(cleanText, "Spanish Female", {
        onend: () => {
          this.isSpeaking = false
        }
      })
    }
  }
}

</script>
