<template>
  <div class="container mx-auto px-4 py-12">
    <div class="grid lg:grid-cols-2 gap-12">
      <!-- Input Form Section -->
      <div class="space-y-8">
        <div class="flex items-center justify-between mb-8">
          <div class="flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
            </div>
            <h1 class="text-3xl font-bold">Generator</h1>
          </div>
          <button @click="fillSampleData" type="button" class="text-xs px-3 py-1.5 rounded-lg border border-indigo-500/30 bg-indigo-500/10 text-indigo-400 hover:bg-indigo-500/20 transition-all">
            Fill Sample Data
          </button>
        </div>

        <div class="p-8 rounded-2xl border border-slate-800 bg-slate-900/50">
          <form @submit.prevent="generateSalesPage" class="space-y-6">
            <div class="grid md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-slate-400 mb-2">Product Name</label>
                <input v-model="form.product_name" type="text" required class="form-input" placeholder="e.g. UltraWatch X" />
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-400 mb-2">Price / Offer</label>
                <input v-model="form.price" type="text" required class="form-input" placeholder="e.g. Rp 199rb" />
              </div>
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-400 mb-2">Description</label>
              <textarea v-model="form.product_description" required rows="3" class="form-input" placeholder="What is your product about?"></textarea>
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-400 mb-2">Features</label>
              <div v-for="(feature, index) in form.features" :key="index" class="flex gap-2 mb-2">
                <input v-model="form.features[index]" type="text" class="form-input" :placeholder="'Feature ' + (index + 1)" />
                <button type="button" @click="removeFeature(index)" class="p-2 text-rose-400 hover:bg-rose-500/10 rounded-lg">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                </button>
              </div>
              <button type="button" @click="addFeature" class="text-sm font-semibold text-indigo-400 hover:text-indigo-300 flex items-center gap-1 mt-2">
                <span>+</span> Add Feature
              </button>
            </div>

            <button 
              :disabled="generating || refining"
              type="submit" 
              class="w-full py-4 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-500 transition-all flex items-center justify-center gap-2 disabled:opacity-50"
            >
              <template v-if="generating">
                <div class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                Generating...
              </template>
              <template v-else>
                Generate Sales Page
              </template>
            </button>
          </form>
        </div>
      </div>

      <!-- Preview Section -->
      <div class="space-y-6">
        <div class="flex items-center justify-between h-10">
          <h2 class="text-xl font-bold">Live Preview</h2>
          <div v-if="generatedPage && generatedPage.status === 'completed'" class="flex gap-2">
             <button @click="showRefinePanel = !showRefinePanel" class="text-xs px-3 py-1.5 rounded-lg bg-indigo-600 text-white font-bold hover:bg-indigo-700 transition-all">
               ✨ Edit with AI
             </button>
             <button @click="downloadHtml" class="text-xs px-3 py-1.5 rounded-lg border border-slate-700 bg-slate-800 text-slate-300 hover:bg-slate-700 transition-all">
               Download
             </button>
          </div>
        </div>

        <div class="preview-container relative min-h-[600px] max-h-[800px] rounded-3xl border border-slate-800 bg-slate-950 overflow-hidden shadow-2xl flex flex-col">
          <div v-if="!generatedPage && !generating" class="absolute inset-0 flex flex-col items-center justify-center text-slate-400 p-8 text-center">
            <svg class="w-16 h-16 mb-4 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
            <p>Generate a sales page to see the preview here.</p>
          </div>

          <!-- Loading States -->
          <div v-if="generating || refining" class="absolute inset-0 z-50 flex flex-col items-center justify-center bg-slate-900/95 backdrop-blur-md">
            <div class="w-12 h-12 border-4 border-indigo-500 border-t-transparent rounded-full animate-spin mb-4"></div>
            <p class="text-white font-bold text-lg animate-pulse">{{ refining ? 'AI Refinement in progress...' : 'AI is writing your content...' }}</p>
          </div>

          <!-- Content Frame -->
          <div v-if="generatedPage && (generatedPage.status === 'completed' || refining)" class="flex-1">
            <iframe :srcdoc="generatedPage.content_html" class="w-full h-full border-none bg-white" :class="{ 'opacity-30 blur-sm': refining }"></iframe>
          </div>

          <!-- Floating Refine Panel -->
          <Transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="transform translate-y-4 opacity-0"
            enter-to-class="transform translate-y-0 opacity-100"
          >
            <div v-if="showRefinePanel && generatedPage && !refining" class="absolute bottom-6 left-1/2 -translate-x-1/2 w-[90%] z-[60]">
              <div class="bg-slate-900/95 backdrop-blur-xl border border-indigo-500/30 p-4 rounded-2xl shadow-2xl flex gap-3 items-end">
                <div class="flex-1">
                  <p class="text-[10px] uppercase tracking-wider text-indigo-400 font-bold mb-2">Edit this result with AI</p>
                  <textarea 
                    v-model="refineInstruction"
                    placeholder="e.g. Change color to dark mode, or add more features..."
                    class="w-full h-20 px-3 py-2 rounded-xl bg-slate-950 border border-slate-800 text-white text-sm outline-none focus:ring-1 focus:ring-indigo-500 resize-none"
                    @keyup.ctrl.enter="startRefinement"
                  ></textarea>
                </div>
                <button 
                  @click="startRefinement"
                  :disabled="!refineInstruction"
                  class="px-4 py-2 bg-indigo-600 text-white font-bold rounded-xl text-sm hover:bg-indigo-700 disabled:opacity-50 h-10 mb-0.5"
                >
                  Refine
                </button>
              </div>
            </div>
          </Transition>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
const { token } = useAuth()
const config = useRuntimeConfig()
const generating = ref(false)
const refining = ref(false)
const showRefinePanel = ref(false)
const generatedPage = ref(null)
const refineInstruction = ref('')

const form = reactive({
  product_name: '',
  price: '',
  product_description: '',
  features: [''],
  target_audience: '',
  tone: 'Persuasif',
  usp: ''
})

const addFeature = () => form.features.push('')
const removeFeature = (index) => form.features.splice(index, 1)

const fillSampleData = () => {
  form.product_name = 'Kopi Wonogiri Premium'
  form.price = 'Rp 75.000'
  form.product_description = 'Biji kopi pilihan yang diproses secara organik dari lereng pegunungan Wonogiri.'
  form.features = ['High Caffeine', 'Organic Certified']
}

const pollStatus = async (id, isRefining = false) => {
  try {
    const data = await $fetch(`${config.public.apiUrl}/sales-pages/${id}/status`, {
      headers: { Authorization: `Bearer ${token.value}` }
    })
    
    if (data.status === 'completed') {
      generatedPage.value = data
      generating.value = false
      refining.value = false
      if (isRefining) {
        refineInstruction.value = ''
        showRefinePanel.value = false
      }
    } else if (data.status === 'failed') {
      alert('Failed: ' + data.error_message)
      generating.value = false
      refining.value = false
    } else {
      setTimeout(() => pollStatus(id, isRefining), 3000)
    }
  } catch (e) {
    console.error('Polling failed', e)
    generating.value = false
    refining.value = false
  }
}

const generateSalesPage = async () => {
  generating.value = true
  generatedPage.value = null
  try {
    const data = await $fetch(`${config.public.apiUrl}/sales-pages`, {
      method: 'POST',
      headers: { Authorization: `Bearer ${token.value}` },
      body: form
    })
    pollStatus(data.id)
  } catch (e) {
    alert('Failed to start generation.')
    generating.value = false
  }
}

const startRefinement = async () => {
  if (!refineInstruction.value || !generatedPage.value) return
  refining.value = true
  try {
    await $fetch(`${config.public.apiUrl}/sales-pages/${generatedPage.value.id}/refine`, {
      method: 'POST',
      headers: { Authorization: `Bearer ${token.value}` },
      body: { instruction: refineInstruction.value }
    })
    pollStatus(generatedPage.value.id, true)
  } catch (e) {
    console.error('Refinement failed', e)
    alert('Failed to start refinement.')
    refining.value = false
  }
}

const downloadHtml = () => {
  if (generatedPage.value) {
    const blob = new Blob([generatedPage.value.content_html], { type: 'text/html' })
    const url = URL.createObjectURL(blob)
    const a = document.createElement('a')
    a.href = url
    a.download = `sales-page-${generatedPage.value.product_name.toLowerCase()}.html`
    a.click()
  }
}

definePageMeta({
  middleware: (to, from) => {
    const { token } = useAuth()
    if (!token.value) return navigateTo('/login')
  }
})
</script>

<style scoped>
.form-input {
  @apply w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition-all text-slate-200 placeholder:text-slate-600;
}
</style>
