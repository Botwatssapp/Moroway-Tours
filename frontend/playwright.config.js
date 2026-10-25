import {defineConfig} from '@playwright/test';
export default defineConfig({testDir:'./tests',workers:1,fullyParallel:false,timeout:60000,expect:{timeout:15000},use:{baseURL:'http://127.0.0.1:5173',channel:'msedge',headless:true},reporter:'list',outputDir:'../tools/.test-results/browser'});
