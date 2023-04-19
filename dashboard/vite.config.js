import { defineConfig, loadEnv, splitVendorChunkPlugin } from 'vite';
import react from '@vitejs/plugin-react';
import buildpack from './src/utils/buildpack';
import { resolve } from 'path';

// https://vitejs.dev/config/
export default defineConfig(async ({ mode }) => {
  process.env = { ...process.env, ...loadEnv(mode, process.cwd()) };
  const possibleTypes = await buildpack.getPossibleTypes();
  return {
    resolve: {
      alias: {
        '@simpify': resolve(__dirname, './src'),
      },
    },
    plugins: [react(), splitVendorChunkPlugin()],
    define: {
      SIMPIFY_POSSIBLE_TYPES: JSON.stringify(possibleTypes),
    },
    build: {
      outDir: resolve(__dirname, '../pub/dashboard'),
      sourcemap: true,
    },
  };
});
