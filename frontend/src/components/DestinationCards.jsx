import Photo from './Photo';
import {Link} from 'react-router-dom';
import {useSite} from '../context/SiteContext';

export default function DestinationCards(){
 const {destinations,tr}=useSite();
 return <div className="destinations destinations-public">{destinations.map(d=><Link key={d.id} className="destination" to={'/tours?destination='+encodeURIComponent(d.name)}><Photo src={d.image} city={d.name} alt={d.name} title={d.image_credit||undefined} sizes="(max-width:900px) 50vw, 33vw"/><div><h3>{d.name}</h3><small>{tr(d.description)}</small></div></Link>)}</div>;
}
