<template>
  <div class="relative overflow-hidden pt-20 pb-32">
    <!-- Background Glow -->
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-6xl aspect-square bg-indigo-500/10 blur-[120px] rounded-full -z-10"></div>

    <div class="container mx-auto px-4 text-center">
      <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full border border-indigo-500/30 bg-indigo-500/10 text-indigo-400 text-xs font-semibold mb-8">
  <span class="relative flex h-2 w-2">
    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
    <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
  </span>

  Powered by
 <span>
  <a href="https://portofolio.adhinath.my.id/" class="text-indigo-400 hover:underline">
    Muhamad Adhi Winata
  </a>
 </span>
</div>

      <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight mb-8 leading-tight">
        Generate <span class="bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400 bg-clip-text text-transparent">Persuasive Sales Pages</span> <br /> 
        in Seconds with AI
      </h1>

      <p class="max-w-2xl mx-auto text-lg text-slate-400 mb-12">
        Transform raw product data into high-converting sales copies and stunning visual designs. 
        Saas-ready, reactive, and powered by advanced AI.
      </p>

      <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
        <NuxtLink to="/generate" class="px-8 py-4 rounded-xl bg-indigo-600 text-white font-bold text-lg hover:bg-indigo-500 transition-all shadow-xl shadow-indigo-500/30 group">
          Start Generating
          <span class="inline-block transition-transform group-hover:translate-x-1">→</span>
        </NuxtLink>
        <NuxtLink to="/register" class="px-8 py-4 rounded-xl border border-slate-700 bg-slate-900/50 text-white font-bold text-lg hover:bg-slate-800 transition-all">
          Create Account
        </NuxtLink>
      </div>

      <!-- Feature Grid -->
      <div class="grid md:grid-cols-3 gap-8 mt-32">
        <div class="p-8 rounded-2xl border border-slate-800 bg-slate-900/50 backdrop-blur-sm text-left">
          <div class="w-12 h-12 rounded-lg bg-indigo-500/20 flex items-center justify-center mb-6">
            <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
          </div>
          <h3 class="text-xl font-bold mb-3">Lightning Fast</h3>
          <p class="text-slate-400">Generate complete sales pages with copy and styling in under 10 seconds.</p>
        </div>
        <div class="p-8 rounded-2xl border border-slate-800 bg-slate-900/50 backdrop-blur-sm text-left">
          <div class="w-12 h-12 rounded-lg bg-purple-500/20 flex items-center justify-center mb-6">
            <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" /></svg>
          </div>
          <h3 class="text-xl font-bold mb-3">Sleek Design</h3>
          <p class="text-slate-400">Built-in Tailwind CSS patterns ensure your sales pages look modern and professional.</p>
        </div>
        <div class="p-8 rounded-2xl border border-slate-800 bg-slate-900/50 backdrop-blur-sm text-left">
          <div class="w-12 h-12 rounded-lg bg-pink-500/20 flex items-center justify-center mb-6">
            <svg class="w-6 h-6 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
          </div>
          <h3 class="text-xl font-bold mb-3">Persuasive Copies</h3>
          <p class="text-slate-400">Gemini 1.5 Flash uses advanced NLP to write compelling marketing psychology into your copies.</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
definePageMeta({
  layout: 'default'
})
</script>
