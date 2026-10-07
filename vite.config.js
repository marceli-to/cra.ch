import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import { fileURLToPath, URL } from 'node:url';

export default defineConfig(({ command }) => ({
  plugins: [
    laravel({
      input: [
        'resources/sass/web/app.scss',
        'resources/js/web/app.js',
        'resources/sass/cms/app.scss',
        'resources/js/cms/app.js',
      ],
      refresh: true,
    }),
    vue({
      template: {
        transformAssetUrls: {
          // Keep absolute urls (/assets/...) as they are; don't import them
          base: null,
          includeAbsolute: false,
        },
      },
    }),
  ],
  resolve: {
    alias: [
      { find: '@', replacement: fileURLToPath(new URL('./resources/js/cms', import.meta.url)) },
    ],
  },
  // Dev only: serve public/ so the Sass's absolute /assets/... URLs resolve
  // on the Vite server. In a build, publicDir would prefix them with /build/.
  publicDir: command === 'serve' ? 'public' : false,
  css: {
    preprocessorOptions: {
      scss: {
        // The Sass still uses @import; moving to @use is its own job
        silenceDeprecations: ['import', 'global-builtin', 'color-functions'],
      },
    },
  },
}));
