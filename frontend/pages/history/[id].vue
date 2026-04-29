<template>
  <div class="container mx-auto px-4 py-12">
    <div v-if="loading" class="flex justify-center py-20">
      <div class="w-12 h-12 border-4 border-slate-800 border-t-indigo-600 rounded-full animate-spin"></div>
    </div>

    <div v-else-if="page" class="space-y-8">
      <!-- Header Section -->
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <NuxtLink to="/history" class="text-sm text-indigo-400 hover:underline flex items-center gap-1 mb-4">
            ← Back to All Pages
          </NuxtLink>
          <h1 class="text-3xl font-bold">{{ page.product_name }}</h1>
          <p class="text-slate-400">Generated on {{ new Date(page.created_at).toLocaleString() }}</p>
        </div>
        <div class="flex gap-3">
          <button @click="showRefinePanel = !showRefinePanel" class="px-6 py-3 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-700 transition-all flex items-center gap-2">
            <span>✨</span> Edit with AI
          </button>
          <button @click="copyCode" class="px-6 py-3 rounded-xl border border-slate-700 bg-slate-900 text-white font-bold hover:bg-slate-800 transition-all">
            Copy HTML
          </button>
        </div>
      </div>

      <!-- Main Preview Area -->
      <div class="relative">
        <div class="rounded-3xl border border-slate-800 bg-[#0b0b0c] overflow-hidden shadow-2xl relative">
          <iframe 
            v-if="page" 
            :srcdoc="page.content_html" 
            class="w-full h-[800px] border-none"
            :class="{ 'opacity-40 blur-sm pointer-events-none': refining }"
          ></iframe>

          <!-- Refining Overlay -->
          <div v-if="refining" class="absolute inset-0 flex items-center justify-center bg-black/40 backdrop-blur-sm z-20">
            <div class="flex flex-col items-center gap-4">
              <div class="w-16 h-16 border-4 border-indigo-500 border-t-transparent rounded-full animate-spin"></div>
              <p class="text-white font-bold text-xl tracking-wider animate-pulse text-center">
                AI is refining your page...<br/>
                <span class="text-sm font-normal text-indigo-300">This might take a moment</span>
              </p>
            </div>
          </div>
        </div>

        <!-- Floating Refine Panel -->
        <Transition
          enter-active-class="transition duration-300 ease-out"
          enter-from-class="transform translate-y-4 opacity-0 scale-95"
          enter-to-class="transform translate-y-0 opacity-100 scale-100"
          leave-active-class="transition duration-200 ease-in"
          leave-from-class="transform translate-y-0 opacity-100 scale-100"
          leave-to-class="transform translate-y-4 opacity-0 scale-95"
        >
          <div v-if="showRefinePanel" class="absolute bottom-8 left-1/2 -translate-x-1/2 w-full max-w-2xl px-4 z-30">
            <div class="bg-slate-900/90 backdrop-blur-xl border border-indigo-500/30 p-6 rounded-3xl shadow-2xl shadow-indigo-500/10">
              <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                  <span class="text-indigo-400">✨</span> Refine this page
                </h3>
                <button @click="showRefinePanel = false" class="text-slate-500 hover:text-white transition">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
              </div>
              <div class="flex gap-4">
                <textarea
                  v-model="refineInstruction"
                  placeholder="e.g. Change the button color to gold, add a FAQ section, or make the headline more professional..."
                  class="flex-1 h-24 px-4 py-3 rounded-2xl bg-slate-950 border border-slate-800 text-white text-sm focus:ring-2 focus:ring-indigo-500 outline-none resize-none"
                  :disabled="refining"
                  @keyup.ctrl.enter="startRefinement"
                ></textarea>
                <div class="flex flex-col gap-2">
                  <button
                    @click="startRefinement"
                    :disabled="refining || !refineInstruction"
                    class="h-full px-6 rounded-2xl bg-indigo-600 text-white font-bold hover:bg-indigo-700 disabled:opacity-50 transition-all flex flex-col items-center justify-center gap-2"
                  >
                    <span>Apply</span>
                    <span class="text-[10px] opacity-60">Ctrl+Enter</span>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </Transition>
      </div>
    </div>
  </div>
</template>

<script setup>
const route = useRoute()
const { token } = useAuth()
const config = useRuntimeConfig()
const toast = useToast()
const page = ref(null)
const loading = ref(true)
const refining = ref(false)
const showRefinePanel = ref(false)
const refineInstruction = ref('')

const fetchPage = async () => {
  try {
    const data = await $fetch(`${config.public.apiUrl}/sales-pages/${route.params.id}`, {
      headers: { Authorization: `Bearer ${token.value}` }
    })
    page.value = data
  } catch (e) {
    toast.error('Failed to fetch page details.')
    navigateTo('/history')
  } finally {
    loading.value = false
  }
}

const startRefinement = async () => {
  if (!refineInstruction.value) return
  
  refining.value = true
  showRefinePanel.value = false
  
  try {
    await $fetch(`${config.public.apiUrl}/sales-pages/${page.value.id}/refine`, {
      method: 'POST',
      headers: { Authorization: `Bearer ${token.value}` },
      body: { instruction: refineInstruction.value }
    })
    pollStatus()
  } catch (e) {
    console.error('Refinement failed', e)
    toast.error('Failed to start AI refinement.')
    refining.value = false
  }
}

const pollStatus = async () => {
  const pollInterval = setInterval(async () => {
    try {
      const data = await $fetch(`${config.public.apiUrl}/sales-pages/${page.value.id}/status`, {
        headers: { Authorization: `Bearer ${token.value}` }
      })
      
      if (data.status === 'completed') {
        clearInterval(pollInterval)
        await fetchPage()
        refining.value = false
        refineInstruction.value = ''
      } else if (data.status === 'failed') {
        clearInterval(pollInterval)
        refining.value = false
        toast.error('AI Refinement failed: ' + (data.error_message || 'Unknown error'))
      }
    } catch (e) {
      console.error('Polling failed', e)
      clearInterval(pollInterval)
      refining.value = false
    }
  }, 3000)
}

const copyCode = () => {
  if (page.value) {
    navigator.clipboard.writeText(page.value.content_html)
    toast.success('HTML code copied to clipboard!')
  }
}

onMounted(() => {
  fetchPage()
})

definePageMeta({
  middleware: (to, from) => {
    const { token } = useAuth()
    if (!token.value) return navigateTo('/login')
  }
})
</script>
