<template>
  <div class="min-h-screen bg-slate-950 text-slate-200 selection:bg-indigo-500/30">
    <!-- Navbar Doc -->
    <nav class="sticky top-0 z-40 w-full backdrop-blur flex-none transition-colors duration-500 lg:z-50 lg:border-b lg:border-slate-800 bg-slate-950/80">
      <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
          <div class="flex items-center">
            <NuxtLink to="/" class="flex items-center gap-2 group">
              <div class="bg-indigo-600 p-1.5 rounded-lg group-hover:bg-indigo-500 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/></svg>
              </div>
              <span class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-indigo-400 to-purple-400">AI Sales Gen</span>
            </NuxtLink>
            <div class="hidden md:ml-8 md:flex md:space-x-8">
              <span class="text-slate-700">/</span>
              <span class="text-sm font-medium text-slate-400">Dokumentasi</span>
            </div>
          </div>
          <div class="flex items-center gap-4">
            <button @click="startTour" class="hidden md:flex items-center gap-2 px-4 py-2 bg-indigo-500/10 text-indigo-400 rounded-full text-sm font-medium hover:bg-indigo-500/20 transition-all border border-indigo-500/20">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m10 8 4 4-4 4"/></svg>
              Mulai Simulasi
            </button>
            <NuxtLink to="/" class="text-sm font-semibold text-indigo-400 hover:text-indigo-300 transition-colors">Buka Aplikasi &rarr;</NuxtLink>
          </div>
        </div>
      </div>
    </nav>

    <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
      <div class="flex flex-col lg:flex-row gap-12">
        <!-- Sidebar -->
        <aside class="lg:w-64 flex-shrink-0">
          <nav class="sticky top-28 space-y-8">
            <div>
              <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-4">Navigasi</h3>
              <ul class="space-y-3">
                <li>
                  <NuxtLink to="/docs" class="flex items-center gap-2 text-sm text-slate-400 hover:text-indigo-400 transition-colors" active-class="text-indigo-400 font-semibold" exact>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                    Beranda Dok
                  </NuxtLink>
                </li>
                <li>
                  <NuxtLink to="/docs/user-guide" class="flex items-center gap-2 text-sm text-slate-400 hover:text-indigo-400 transition-colors" active-class="text-indigo-400 font-semibold">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/><path d="M8 7h6"/><path d="M8 11h8"/></svg>
                    User Guide
                  </NuxtLink>
                </li>
                <li>
                  <NuxtLink to="/docs/spec" class="flex items-center gap-2 text-sm text-slate-400 hover:text-indigo-400 transition-colors" active-class="text-indigo-400 font-semibold">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M9 17h6"/><path d="M9 12h6"/><path d="M9 7h6"/></svg>
                    Spesifikasi Teknis
                  </NuxtLink>
                </li>
              </ul>
            </div>
          </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 min-w-0">
          <div class="bg-slate-900/50 backdrop-blur-sm rounded-2xl shadow-xl border border-slate-800 p-8 sm:p-12 overflow-hidden relative">
            <!-- Glassmorphism decorative element -->
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-indigo-600/10 rounded-full blur-3xl opacity-50"></div>
            
            <ContentDoc>
              <template #default="{ doc }">
                <article class="prose prose-invert prose-indigo max-w-none prose-headings:font-bold prose-a:text-indigo-400 prose-img:rounded-xl prose-img:shadow-2xl prose-img:border prose-img:border-slate-800">
                  <h1 class="text-4xl font-extrabold text-white mb-8 tracking-tight">{{ doc.title || 'Dokumentasi' }}</h1>
                  <ContentRenderer :value="doc" />
                </article>
              </template>
              <template #empty>
                <div class="text-center py-20">
                  <h1 class="text-2xl font-bold text-white">Halaman Kosong</h1>
                  <p class="text-slate-400 mt-2">Belum ada konten untuk halaman ini.</p>
                </div>
              </template>
              <template #not-found>
                <div class="text-center py-20">
                  <h1 class="text-2xl font-bold text-white">404 - Not Found</h1>
                  <p class="text-slate-400 mt-2">Konten dokumentasi tidak ditemukan.</p>
                </div>
              </template>
            </ContentDoc>
          </div>

          <!-- Footer Doc -->
          <footer class="mt-12 pt-8 border-t border-slate-800 flex flex-col sm:flex-row justify-between items-center gap-4 text-sm text-slate-500">
            <p>&copy; 2026 AI Sales Page Generator. Crafted with passion.</p>
            <div class="flex gap-6">
              <a href="#" class="hover:text-indigo-400 transition-colors">Bantuan</a>
              <a href="#" class="hover:text-indigo-400 transition-colors">Kebijakan Privasi</a>
            </div>
          </footer>
        </main>
      </div>
    </div>
  </div>
</template>

<script setup>
const startTour = () => {
  // logic to start tour, will be implemented in composable
  if (window.startProductTour) {
    window.startProductTour()
  } else {
    navigateTo('/login')
  }
}
</script>

<style>
/* Custom prose styles for a more premium look in Dark Mode */
.prose h1 { @apply text-white tracking-tight; }
.prose h2 { @apply text-slate-100 border-b border-slate-800 pb-2 mt-12 mb-6; }
.prose h3 { @apply text-slate-200; }
.prose p { @apply leading-relaxed text-slate-400; }
.prose strong { @apply text-slate-200; }
.prose code { @apply bg-slate-800 text-indigo-400 px-1.5 py-0.5 rounded font-medium border border-slate-700; }
.prose pre { @apply bg-slate-950 text-slate-200 rounded-xl p-6 border border-slate-800 overflow-x-auto my-8; }
.prose blockquote { @apply border-l-4 border-indigo-500 bg-indigo-500/5 py-1 px-4 italic text-slate-300 rounded-r-lg; }
</style>
