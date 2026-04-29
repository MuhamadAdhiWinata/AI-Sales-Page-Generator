<template>
  <NuxtLayout>
    <NuxtPage />
  </NuxtLayout>
</template>

<style>
/* Driver.js Dark Theme */
.driverjs-dark-theme {
  background-color: #0f172a !important; /* slate-900 */
  color: #f1f5f9 !important; /* slate-100 */
  border: 1px solid #1e293b !important; /* slate-800 */
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5) !important;
}

.driverjs-dark-theme .driver-popover-title {
  color: #ffffff !important;
  font-weight: 700 !important;
}

.driverjs-dark-theme .driver-popover-description {
  color: #94a3b8 !important; /* slate-400 */
}

.driverjs-dark-theme .driver-popover-progress-text {
  color: #64748b !important; /* slate-500 */
}

.driverjs-dark-theme .driver-popover-arrow {
  border-color: #0f172a !important;
}

.driverjs-dark-theme .driver-popover-navigation-btns button {
  background-color: #4f46e5 !important; /* indigo-600 */
  color: white !important;
  text-shadow: none !important;
  border: none !important;
  border-radius: 6px !important;
  padding: 5px 12px !important;
}

.driverjs-dark-theme .driver-popover-navigation-btns button:hover {
  background-color: #4338ca !important; /* indigo-700 */
}

.driverjs-dark-theme .driver-popover-close-btn {
  color: #94a3b8 !important;
}
</style>
