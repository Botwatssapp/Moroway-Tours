import {BrowserRouter,Routes,Route,Navigate} from 'react-router-dom';
import {SiteProvider} from './context/SiteContext';
import SiteLayout from './layouts/SiteLayout';
import Editorial from './components/Editorial';
import {ApiState} from './components/Common';
import Tours from './pages/Tours';
import TourDetails from './pages/TourDetails';
import {Booking,Contact} from './pages/Forms';
import {Destinations,GalleryPage,Legal,NotFound} from './pages/OtherPages';
import Admin from './pages/Admin';
import LegacyRedirect from './components/LegacyRedirect';
import {legacyPath} from './utils/routes';
if(window.location.hash&&/^#(?:home|experience|experiences|collection|departure|destination|booking|custom|students|about|services|contact|admin|gallery|destinations|terms|privacy|faq)(?:\/|$)/.test(window.location.hash))history.replaceState(null,'',legacyPath(window.location.hash));
const ready=child=><ApiState>{child}</ApiState>;
export default function App(){return <SiteProvider><BrowserRouter><LegacyRedirect/><Routes><Route element={<SiteLayout/>}><Route index element={ready(<Editorial page="home"/>)}/><Route path="about" element={<Editorial page="about"/>}/><Route path="services" element={<Editorial page="services"/>}/><Route path="faq" element={<section className="section"><Editorial page="faq"/></section>}/><Route path="destinations" element={ready(<Destinations/>)}/><Route path="tours" element={ready(<Tours/>)}/><Route path="tours/:slug" element={<TourDetails/>}/><Route path="gallery" element={ready(<GalleryPage/>)}/><Route path="contact" element={<Contact/>}/><Route path="booking/:tourId" element={ready(<Booking/>)}/><Route path="booking" element={<Navigate to="/custom" replace/>}/><Route path="custom" element={ready(<Booking type="custom"/>)}/><Route path="students" element={ready(<Booking type="students"/>)}/><Route path="admin" element={<Admin/>}/><Route path="terms" element={<Legal/>}/><Route path="privacy" element={<Legal privacy/>}/><Route path="experiences" element={<Navigate to="/tours" replace/>}/><Route path="*" element={<NotFound/>}/></Route></Routes></BrowserRouter></SiteProvider>}
