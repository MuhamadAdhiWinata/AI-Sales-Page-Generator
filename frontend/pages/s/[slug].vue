<template>
  <div class="w-full h-screen bg-[#0b0b0c]">
    <iframe
      v-if="content"
      ref="iframeRef"
      :srcdoc="content"
      class="w-full h-full border-none"
      @load="onIframeLoad"
    ></iframe>
    
    <!-- Loading State -->
    <div v-if="loading" class="fixed inset-0 flex items-center justify-center bg-[#0b0b0c] text-white z-50">
      <div class="flex flex-col items-center gap-4">
        <div class="w-12 h-12 border-4 border-indigo-500 border-t-transparent rounded-full animate-spin"></div>
        <p class="animate-pulse font-medium">Memuat Landing Page...</p>
      </div>
    </div>
  </div>
</template>

<script setup>
const route = useRoute()
const config = useRuntimeConfig()
const content = ref('')
const loading = ref(true)
const iframeRef = ref(null)

const onIframeLoad = () => {
  loading.value = false
}

onMounted(async () => {
  try {
    const data = await $fetch(`${config.public.apiUrl}/s/${route.params.slug}`)
    content.value = data.content_html
    if (data.product_name) {
      useHead({
        title: data.product_name
      })
    }
  } catch (e) {
    console.error('Gagal memuat landing page', e)
    loading.value = false
  }
})

definePageMeta({
  layout: false,
  auth: false
})
</script>

<style scoped>
iframe {
  display: block;
  width: 100%;
  height: 100vh;
}
</style>
