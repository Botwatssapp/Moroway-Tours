import {useEffect,useState} from 'react';
import {useSite} from '../context/SiteContext';
import photos from '../assets/photos.json';

export const photoById=id=>photos.find(photo=>photo.id===id);
export const photoByPath=path=>photos.find(photo=>photo.path===path||photo.variants?.some(v=>v.src===path));
export default function Photo({photo,src,alt,city,priority=false,className='',style,srcSet,sizes='(max-width:600px) 100vw, (max-width:1100px) 50vw, 900px',loading,...props}){
 const {lang}=useSite();const record=photo||photoByPath(src);const primary=src||record?.path;
 const [failed,setFailed]=useState(false);const [unavailable,setUnavailable]=useState(false);
 useEffect(()=>{setFailed(false);setUnavailable(false)},[primary]);
 const label=(lang==='en'?(record?.alt_en||record?.alt?.en):record?.alt?.fr)||alt||record?.alt||city||'MOROWAY TOURS';
 const fallback=record?.fallback||'/assets/destinations/'+({'Fès':'fes','Tanger':'tanger','Casablanca':'casablanca'}[city||record?.city]||'fes')+'.jpg';
 if(unavailable)return <span className={'photo-unavailable '+className} style={style} role="img" aria-label={typeof label==='string'?label:alt}><span aria-hidden="true">◇</span><span>{city||record?.city||'MOROWAY TOURS'}</span><small>{lang==='fr'?'Photographie temporairement indisponible':'Photograph temporarily unavailable'}</small></span>;
 const displayedLabel=failed?(lang==='fr'?'Vue de '+(city||record?.city||'la destination')+' — photographie de remplacement':'View of '+(city||record?.city||'the destination')+' — replacement photograph'):(typeof label==='string'?label:alt);
 return <img {...props} className={className} src={failed?fallback:primary} srcSet={failed?undefined:srcSet||record?.src_set} sizes={failed?undefined:sizes} alt={displayedLabel} data-fallback={failed||undefined} width={record?.width} height={record?.height} loading={priority?'eager':loading||'lazy'} fetchPriority={priority?'high':'auto'} decoding="async" style={{objectFit:'cover',objectPosition:record?.position_css||'50% 50%',...style}} title={failed?displayedLabel:props.title||record?.credit} onError={()=>{if(failed)setUnavailable(true);else setFailed(true)}}/>;
}
