import { fileURLToPath, URL } from 'node:url'

import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [
    vue(),
  ],
  server: {
      port: 3000,
      strictPort: true,
      hmr: {
          port: 9000
      },
  },
  base: './' ,
  rollupOptions: {
    // Specify the entry point of your application
    input: 'src/main.js', // This should point to the main entry file of your Vue app
  },

  optimizeDeps: {
    // Specify any dependencies that should be pre-bundled
    include: ['vue', 'vue-router'], // Add any other dependencies your app uses
  },
})
