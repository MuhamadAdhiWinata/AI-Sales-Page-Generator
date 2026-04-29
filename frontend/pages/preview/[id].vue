<template>
  <div class="w-full h-screen bg-[#0b0b0c]">
    <iframe
      v-if="content"
      ref="iframeRef"
      :srcdoc="content"
      class="w-full h-full border-none"
      @load="loading = false"
    ></iframe>
    
    <div v-if="loading" class="fixed inset-0 flex items-center justify-center bg-[#0b0b0c] text-white z-50">
      <div class="flex flex-col items-center gap-4">
        <div class="w-12 h-12 border-4 border-indigo-500 border-t-transparent rounded-full animate-spin"></div>
        <p class="animate-pulse">Menyiapkan Preview...</p>
      </div>
    </div>

    <NuxtLink 
      v-if="!loading && content" 
      :to="`/history/${route.params.id}`" 
      class="fixed bottom-6 right-6 px-6 py-3 bg-indigo-600 text-white rounded-full font-bold shadow-lg shadow-indigo-500/30 hover:bg-indigo-500 transition-all flex items-center gap-2 z-50"
    >
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
      Edit with AI
    </NuxtLink>
  </div>
</template>

<script setup>
const route = useRoute()
const { token } = useAuth()
const config = useRuntimeConfig()
const content = ref('')
const loading = ref(true)

onMounted(async () => {
  try {
    const data = await $fetch(`${config.public.apiUrl}/sales-pages/${route.params.id}`, {
      headers: {
        Authorization: `Bearer ${token.value}`
      }
    })
    content.value = data.content_html
  } catch (e) {
    console.error('Failed to load preview', e)
    loading.value = false
  }
})

definePageMeta({
  layout: false
})
</script>
