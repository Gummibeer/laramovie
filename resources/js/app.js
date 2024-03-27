import Alpine from 'alpinejs';
import intersect from '@alpinejs/intersect';
import search from './alpine/search';

Alpine.plugin(intersect);
Alpine.data('search', search);

Alpine.start();
