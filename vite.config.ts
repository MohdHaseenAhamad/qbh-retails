import { defineConfig } from 'laravel-vite'
import vue from '@vitejs/plugin-vue'

export default defineConfig({
    build:{
        rollupOptions:{
            output:{
                manualChunks:undefined
            }
        },
        target:"esnext",
        minify: true,
        chunkSizeWarningLimit:1024*1024*1024
    },
    server: {
        watch: {
            ignored: ['**/.env/**'],
        },
    },
    resolve: {
        alias: {
            "vue-i18n": "vue-i18n/dist/vue-i18n.cjs.js"
        }
    }
}).withPlugins(
    vue
)
