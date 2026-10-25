import {useEffect} from 'react';
import {useNavigate} from 'react-router-dom';
import {legacyPath} from '../utils/routes';
export default function LegacyRedirect(){const navigate=useNavigate();useEffect(()=>{const redirect=()=>{if(/^#(?:home|experience|experiences|collection|departure|destination|booking|custom|students|about|services|contact|admin|gallery|destinations|terms|privacy|faq)(?:\/|$)/.test(window.location.hash))navigate(legacyPath(window.location.hash),{replace:true})};window.addEventListener('hashchange',redirect);redirect();return()=>window.removeEventListener('hashchange',redirect)},[navigate]);return null;}
