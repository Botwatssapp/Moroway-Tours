import axios from 'axios';
export const api=axios.create({baseURL:import.meta.env.VITE_API_URL||'',withCredentials:true,withXSRFToken:true,headers:{Accept:'application/json','X-Requested-With':'XMLHttpRequest'}});
export async function csrf(){await api.get('/sanctum/csrf-cookie');}
export async function submit(path,data){await csrf();return (await api.post(path,data)).data;}
export function errorMessage(error){if(error.response?.data?.errors)return Object.values(error.response.data.errors).flat().join(' ');return error.response?.data?.message||'Connexion au serveur impossible. Vérifiez que Laravel est démarré, puis réessayez.';}
