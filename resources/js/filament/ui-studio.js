import Sortable from 'sortablejs';

window.Sortable = Sortable;
window.dispatchEvent(new CustomEvent('titan-ui-studio:sortable-ready'));
