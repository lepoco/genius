import React from 'react';
import ReactDOM from 'react-dom';
import { BrowserRouter as Router } from 'react-router-dom';
import App from './App';
//import registerServiceWorker from "./registerServiceWorker";

import './styles/app.scss';

const baseUrl = document.getElementsByTagName('base')[0].getAttribute('href');

ReactDOM.render(
  <Router basename={baseUrl ?? ''}>
    <App />
  </Router>,
  document.getElementById('root'),
);

//registerServiceWorker();
