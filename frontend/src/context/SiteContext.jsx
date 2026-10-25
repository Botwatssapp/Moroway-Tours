import {createContext,useContext,useState,useEffect,useCallback} from 'react';
import {api,errorMessage} from '../services/api';
const Context=createContext(null);
let pendingLoad;
export function SiteProvider({children}){
 const [lang,setLang]=useState(()=>localStorage.getItem('moroway-language')||'fr');
 const [data,setData]=useState({tours:[],destinations:[],settings:{},reviews:[]});const [loading,setLoading]=useState(true);const [error,setError]=useState('');
 const reload=useCallback(async()=>{setLoading(true);setError('');try{pendingLoad??=Promise.all(['tours','destinations','settings','reviews'].map(p=>api.get('/api/'+p))).finally(()=>{pendingLoad=null});const results=await pendingLoad;setData(Object.fromEntries(['tours','destinations','settings','reviews'].map((p,i)=>[p,results[i].data])));}catch(e){setError(errorMessage(e));}finally{setLoading(false);}},[]);
 useEffect(()=>{reload()},[reload]);useEffect(()=>{localStorage.setItem('moroway-language',lang);document.documentElement.lang=lang;},[lang]);
 const t=(fr,en)=>lang==='fr'?fr:en;const tr=value=>Array.isArray(value)?value[lang==='fr'?0:1]:typeof value==='object'&&value?value[lang]||value.fr: value||'';
 return <Context.Provider value={{...data,loading,error,reload,lang,setLang,t,tr}}>{children}</Context.Provider>;
}
export const useSite=()=>useContext(Context);
