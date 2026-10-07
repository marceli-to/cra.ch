import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig(({ command }) => ({
  plugins: [
    laravel({
      input: [
        'resources/sass/web/app.scss',
        'resources/js/web/app.js',
      ],
      refresh: true,
    }),
  ],
  // Dev only: serve public/ so the Sass's absolute /assets/... URLs resolve
  // on the Vite server. In a build, publicDir would prefix them with /build/.
  publicDir: command === 'serve' ? 'public' : false,
  css: {
    preprocessorOptions: {
      scss: {
        // The Sass still uses @import; moving to @use is its own job
        silenceDeprecations: ['import', 'global-builtin', 'color-functions', 'mixed-decls'],
      },
    },
  },
}));
