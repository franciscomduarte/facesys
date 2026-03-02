import './bootstrap';

import Alpine from 'alpinejs';
import facialMap from './components/facial-map';

Alpine.data('facialMap', facialMap);

window.Alpine = Alpine;

Alpine.start();
