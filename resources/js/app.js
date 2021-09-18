import Alpine from 'alpinejs';
import intersect from '@alpinejs/intersect';
import search from './alpine/search';
import groupedIndex from './alpine/groupedIndex';

Alpine.plugin(intersect);
Alpine.data('search', search);
Alpine.data('groupedIndex', groupedIndex);

Alpine.start();
