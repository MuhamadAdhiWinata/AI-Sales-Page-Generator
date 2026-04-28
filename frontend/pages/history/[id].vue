<template>
  <div class="container mx-auto px-4 py-12">
    <div v-if="loading" class="flex justify-center py-20">
      <div class="w-12 h-12 border-4 border-slate-800 border-t-indigo-600 rounded-full animate-spin"></div>
    </div>

    <div v-else-if="page" class="space-y-8">
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <NuxtLink to="/history" class="text-sm text-indigo-400 hover:underline flex items-center gap-1 mb-4">
            ← Back to History
          </NuxtLink>
          <h1 class="text-3xl font-bold">{{ page.product_name }}</h1>
          <p class="text-slate-400">Generated on {{ new Date(page.created_at).toLocaleString() }}</p>
        </div>
        <div class="flex gap-3">
          <button @click="copyCode" class="px-6 py-3 rounded-xl border border-slate-700 bg-slate-900 text-white font-bold hover:bg-slate-800 transition-all">
            Copy HTML
          </button>
        </div>
      </div>

      <div class="rounded-3xl border border-slate-800 bg-[#0b0b0c] overflow-hidden">
        <iframe 
          v-if="page" 
          :srcdoc="page.content_html" 
          class="w-full h-[800px] border-none shadow-2xl"
        ></iframe>
      </div>
    </div>
  </div>
</template>

<script setup>
const route = useRoute()
const { token } = useAuth()
const config = useRuntimeConfig()
const page = ref(null)
const loading = ref(true)

const fetchPage = async () => {
  try {
    const data = await $fetch(`${config.public.apiUrl}/sales-pages/${route.params.id}`, {
      headers: {
        Authorization: `Bearer ${token.value}`
      }
    })
    page.value = data
  } catch (e) {
    alert('Failed to fetch page details.')
    navigateTo('/history')
  } finally {
    loading.value = false
  }
}

const copyCode = () => {
  if (page.value) {
    navigator.clipboard.writeText(page.value.content_html)
    alert('HTML code copied to clipboard!')
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
