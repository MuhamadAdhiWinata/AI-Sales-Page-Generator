<template>
  <div class="container mx-auto px-4 py-12">
    <div class="grid lg:grid-cols-2 gap-12">
      <!-- Input Form Section -->
      <div class="space-y-8">
        <div class="flex items-center gap-4 mb-8">
          <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
          </div>
          <h1 class="text-3xl font-bold">Generator</h1>
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

            <div>
              <label class="block text-sm font-medium text-slate-400 mb-2">Unique Selling Point (USP)</label>
              <input v-model="form.usp" type="text" required class="form-input" placeholder="What makes you different?" />
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
          <div v-if="generatedPage" class="flex gap-2">
             <button @click="copyCode" class="text-xs px-3 py-1.5 rounded-lg border border-slate-700 bg-slate-800 hover:bg-slate-700 transition-colors">Copy Code</button>
          </div>
        </div>

        <div class="preview-container relative min-h-[600px] rounded-2xl border border-slate-800 bg-white overflow-hidden">
          <div v-if="!generatedPage && !generating" class="absolute inset-0 flex flex-col items-center justify-center text-slate-400 p-8 text-center">
            <svg class="w-16 h-16 mb-4 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
            <p>Fill out the form and click generate to see your sales page here.</p>
          </div>

          <div v-if="generating" class="absolute inset-0 flex flex-col items-center justify-center bg-slate-50">
            <div class="w-12 h-12 border-4 border-indigo-200 border-t-indigo-600 rounded-full animate-spin mb-4"></div>
            <p class="text-slate-600 font-medium animate-pulse">AI is writing your copy...</p>
          </div>

          <!-- The actual generated content -->
          <div v-if="generatedPage" class="h-full overflow-y-auto" v-html="generatedPage.content_html"></div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
const { token } = useAuth()
const config = useRuntimeConfig()
const generating = ref(false)
const generatedPage = ref(null)

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

const generateSalesPage = async () => {
  if (!token.value) return navigateTo('/login')
  
  generating.value = true
  try {
    const data = await $fetch(`${config.public.apiUrl}/sales-pages`, {
      method: 'POST',
      headers: {
        Authorization: `Bearer ${token.value}`
      },
      body: form
    })
    generatedPage.value = data
  } catch (e) {
    alert(e.data?.error || 'Failed to generate sales page.')
  } finally {
    generating.value = false
  }
}

const copyCode = () => {
  if (generatedPage.value) {
    navigator.clipboard.writeText(generatedPage.value.content_html)
    alert('HTML code copied to clipboard!')
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
