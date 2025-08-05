<template>
  <div class="container-fluid vh-100">
    <div class="row h-100">
      <!-- Columna izquierda -->
      <div class="col-12 col-md-3 d-flex flex-column justify-content-center align-items-center">
        <div class="text-cyan text-center">
          <div class="app mb-3">Command Prompt</div>
          <div class="app mb-3">RE</div>
          <div class="app mb-3">Jarvis</div>
        </div>
      </div>

      <!-- Columna central con micrófono -->
      <div class="col-12 col-md-6 d-flex flex-column justify-content-center align-items-center">
        <div
          class="mic-hologram mb-3"
          :class="{ listening, speaking }"
          @click="startListening"
        ></div>
      </div>

      <!-- Columna derecha -->
      <div class="col-12 col-md-3 d-flex flex-column justify-content-center align-items-center">
        <div class="text-cyan text-center">
          <div class="app mb-3">Kali Active</div>
          <div class="app mb-3">PowerShell</div>
          <div class="app mb-3">My PC</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import { ElNotification } from 'element-plus'

export default {
  name: 'HologramAssistant',
  data() {
    return {
      listening: false,
      speaking: false,
    }
  },
  mounted() {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content')
  },
  methods: {
    async speak(text) {
      if (!text) return

      this.speaking = true

      try {
        const response = await fetch('/assistent/speak-local', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify({ text })
        })

        // ⚠️ Verifica si hay error de cuota
        if (response.status === 500) {
          const errorText = await response.text()
          if (errorText.includes('quota_exceeded')) {
            ElNotification({
              title: 'Límite de voz alcanzado',
              message: '⚠️ Se han agotado los créditos de voz. Las respuestas serán solo en texto.',
              type: 'warning',
              duration: 5000,
              showClose: true
            })
            this.speaking = false
            return
          }
        }

        const blob = await response.blob()

        if (blob.type.startsWith('audio')) {
          const url = URL.createObjectURL(blob)
          const audio = new Audio(url)

          audio.onended = () => {
            this.speaking = false
          }

          audio.play().catch(err => {
            console.error('❌ Error al reproducir audio:', err)
            this.speaking = false
          })
        } else {
          ElNotification({
            title: 'Error de audio',
            message: 'No se pudo reproducir la respuesta. Puede que no sea un archivo de audio válido.',
            type: 'error',
            duration: 5000,
            showClose: true
          })
          this.speaking = false
        }
      } catch (error) {
        console.error('❌ Error al reproducir audio:', error)
        ElNotification({
          title: 'Error de conexión',
          message: 'Hubo un problema al intentar generar la voz.',
          type: 'error',
          duration: 5000,
          showClose: true
        })
        this.speaking = false
      }
    },

    stopSpeaking() {
      // No es necesario si usas ElevenLabs, pero puedes pausar audio si lo guardas como instancia
    },

    async startListening() {
      const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition
      if (!SpeechRecognition) {
        alert('Tu navegador no soporta reconocimiento de voz')
        return
      }

      this.stopSpeaking()
      const recognition = new SpeechRecognition()
      recognition.lang = 'es-MX'
      recognition.interimResults = false
      recognition.maxAlternatives = 1

      this.listening = true

      recognition.onresult = async (event) => {
        const transcript = event.results[0][0].transcript
        this.listening = false
        
        try {
          const response = await axios.post('/assistent/assistentSendMessage', { prompt: transcript })
          const reply = response.data.reply

          this.speak(reply)
        } catch (error) {
          console.error('❌ Error al comunicarse con Jarvis:', error)
          this.speak('Lo siento, hubo un error al procesar tu solicitud.')
        }
      }

      recognition.onerror = () => {
        this.listening = false
      }

      recognition.onend = () => {
        this.listening = false
      }

      recognition.start()
    },
  }
}
</script>

<style scoped>
@import "@/Assets/hologram.css";
</style>
