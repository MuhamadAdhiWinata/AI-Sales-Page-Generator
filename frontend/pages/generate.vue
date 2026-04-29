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
            Isi Data Sampel
          </button>
        </div>

        <div class="p-8 rounded-2xl border border-slate-800 bg-slate-900/50">
          <form @submit.prevent="generateSalesPage" class="space-y-6">
            <!-- Product Info -->
            <div class="grid md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-slate-400 mb-2">Product Name</label>
                <input v-model="form.product_name" type="text" required class="form-input" placeholder="e.g. UltraWatch X" />
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-400 mb-2">Price / Offer</label>
                <input v-model="form.price" type="text" required class="form-input" placeholder="e.g. $199 (Limited Offer)" />
              </div>
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-400 mb-2">Product Description</label>
              <textarea v-model="form.product_description" required rows="3" class="form-input" placeholder="What is your product about?"></textarea>
            </div>

            <!-- Features -->
            <div>
              <label class="block text-sm font-medium text-slate-400 mb-2">Key Features</label>
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

            <!-- Demographics & USP -->
            <div class="grid md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-slate-400 mb-2">Target Audience</label>
                <input v-model="form.target_audience" type="text" required class="form-input" placeholder="e.g. Fitness Enthusiasts" />
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-400 mb-2">Tone Selection</label>
                <select v-model="form.tone" class="form-input">
                  <option value="Persuasif">Persuasif</option>
                  <option value="Formal">Formal</option>
                  <option value="Urgen">Urgen (Urgency)</option>
                </select>
              </div>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-slate-400 mb-2">Unique Selling Point (USP)</label>
                <input v-model="form.usp" type="text" required class="form-input" placeholder="What makes you different?" />
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-400 mb-2">Template Style</label>
                <select v-model="form.template" class="form-input">
                  <option value="classic">Classic</option>
                  <option value="neon">Neon</option>
                  <option value="pastel">Pastel</option>
                </select>
              </div>
            </div>

            <button 
              :disabled="generating"
              type="submit" 
              class="w-full py-4 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-500 transition-all flex items-center justify-center gap-2 disabled:opacity-50"
            >
              <template v-if="generating">
                <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
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
      <div class="space-y-8">
        <div class="flex items-center justify-between h-10 mb-8">
          <h2 class="text-xl font-bold">Live Preview</h2>
          <div v-if="generatedPage && generatedPage.status === 'completed'" class="flex gap-2">
             <button @click="downloadHtml" class="text-xs px-3 py-1.5 rounded-lg border border-indigo-500/30 bg-indigo-500/10 text-indigo-400 hover:bg-indigo-500/20 transition-all flex items-center gap-1">
               <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
               Download
             </button>
             <NuxtLink :to="`/s/${generatedPage.slug}`" target="_blank" class="text-xs px-3 py-1.5 rounded-lg border border-slate-700 bg-slate-800 hover:bg-slate-700 transition-colors flex items-center gap-1">
               <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
               Full Page
             </NuxtLink>
             <button @click="copyCode" class="text-xs px-3 py-1.5 rounded-lg border border-slate-700 bg-slate-800 hover:bg-slate-700 transition-colors">Copy</button>
          </div>
        </div>

        <div class="preview-container relative min-h-[600px] max-h-[800px] rounded-2xl border border-slate-800 bg-slate-950 overflow-hidden flex flex-col">
          <div v-if="!generatedPage && !generating" class="absolute inset-0 flex flex-col items-center justify-center text-slate-400 p-8 text-center">
            <svg class="w-16 h-16 mb-4 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
            <p>Fill out the form and click generate to see your sales page here.</p>
          </div>

          <div v-if="generating" class="absolute inset-0 z-50 flex flex-col items-center justify-center bg-slate-900/95 backdrop-blur-md">
            <div class="w-12 h-12 border-4 border-indigo-200 border-t-indigo-600 rounded-full animate-spin mb-4"></div>
            <p class="text-white font-medium animate-pulse text-lg">AI sedang membuat konten...</p>
            <p class="text-slate-400 text-sm mt-2">Ini mungkin memakan waktu 30-60 detik</p>
          </div>

          <!-- The actual generated content -->
          <div v-if="generatedPage && generatedPage.status === 'completed'" class="flex-1">
            <iframe :srcdoc="generatedPage.content_html" class="w-full h-[600px] border-none bg-white"></iframe>
          </div>
          <div v-else-if="generatedPage && generatedPage.status === 'processing'" class="flex-1 flex flex-col items-center justify-center bg-slate-950 text-slate-400">
            <div class="w-10 h-10 border-4 border-indigo-500/20 border-t-indigo-500 rounded-full animate-spin mb-4"></div>
            <p>Wait a moment, AI is processing...</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
const { token } = useAuth()
const config = useRuntimeConfig()
const toast = useToast()
const generating = ref(false)
const generatedPage = ref(null)

const form = reactive({
  product_name: '',
  price: '',
  product_description: '',
  features: [''],
  target_audience: '',
  tone: 'Persuasif',
  template: 'classic',
  usp: ''
})

const addFeature = () => form.features.push('')
const removeFeature = (index) => form.features.splice(index, 1)

const fillSampleData = () => {
  form.product_name = 'Kopi Wonogiri Premium'
  form.price = 'Rp 75.000 (Promo Beli 1 Gratis 1)'
  form.product_description = 'Biji kopi pilihan yang diproses secara organik dari lereng pegunungan Wonogiri, menghasilkan cita rasa yang kuat namun lembut di lambung.'
  form.features = ['High Caffeine', 'Organic Certified', 'Eco-friendly Packaging']
  form.target_audience = 'Pekerja kantoran usia 25-40 yang sibuk dan pecinta kopi'
  form.tone = 'Persuasif'
  form.template = 'classic'
  form.usp = 'Satu-satunya kopi dengan aroma melati alami dari pegunungan tanpa perasa buatan'
}

const pollStatus = async (id) => {
  try {
    const data = await $fetch(`${config.public.apiUrl}/sales-pages/${id}/status`, {
      headers: {
        Authorization: `Bearer ${token.value}`
      }
    })
    
    if (data.status === 'completed') {
      generatedPage.value = data
      generating.value = false
    } else if (data.status === 'failed') {
      toast.error('Generation failed: ' + data.error_message)
      generating.value = false
    } else {
      // Still processing, poll again in 2 seconds
      setTimeout(() => pollStatus(id), 2000)
    }
  } catch (e) {
    console.error('Polling failed', e)
    generating.value = false
  }
}

const generateSalesPage = async () => {
  if (!token.value) return navigateTo('/login')
  
  generating.value = true
  generatedPage.value = null
  
  try {
    const data = await $fetch(`${config.public.apiUrl}/sales-pages`, {
      method: 'POST',
      headers: {
        Authorization: `Bearer ${token.value}`
      },
      body: form
    })
    
    // Start polling
    pollStatus(data.id)
  } catch (e) {
    toast.error(e.data?.error || 'Failed to start generation.')
    generating.value = false
  }
}

const copyCode = () => {
  if (generatedPage.value) {
    navigator.clipboard.writeText(generatedPage.value.content_html)
    toast.success('HTML code copied to clipboard!')
  }
}

const downloadHtml = () => {
  if (generatedPage.value) {
    const fullHtml = `<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>${generatedPage.value.product_name}</title>
    <script src="https://cdn.tailwindcss.com"></` + `script>
    <style>
        body { opacity: 0; transition: opacity 0.6s ease-in; }
    </style>
</head>
<body class="bg-slate-950" onload="document.body.style.opacity='1'">
    ${generatedPage.value.content_html}
</body>
</html>`;

    const blob = new Blob([fullHtml], { type: 'text/html' })
    const url = URL.createObjectURL(blob)
    const a = document.createElement('a')
    a.href = url
    a.download = `sales-page-${generatedPage.value.product_name.replace(/\s+/g, '-').toLowerCase()}.html`
    document.body.appendChild(a)
    a.click()
    document.body.removeChild(a)
    URL.revokeObjectURL(url)
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

.preview-container {
  /* Nuxt v-html content needs specific styling if needed, but Tailwind should work globally */
}
</style>
