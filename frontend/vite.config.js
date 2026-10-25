import {defineConfig} from 'vite';
import react from '@vitejs/plugin-react';
export default defineConfig({plugins:[react()],server:{port:5173,strictPort:true,proxy:{'/api':'http://127.0.0.1:8000','/sanctum':'http://127.0.0.1:8000','/login':'http://127.0.0.1:8000','/logout':'http://127.0.0.1:8000','/sitemap.xml':'http://127.0.0.1:8000','/storage':'http://127.0.0.1:8000'}}});
