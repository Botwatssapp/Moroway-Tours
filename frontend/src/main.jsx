import React from 'react';
import {createRoot} from 'react-dom/client';
import App from './App';
import './styles/original.css';
import './styles/application.css';
import './styles/destinations.css';
import './styles/photos.css';
createRoot(document.getElementById('root')).render(<React.StrictMode><App/></React.StrictMode>);
