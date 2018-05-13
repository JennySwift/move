import Vue from 'vue'

import Navbar from './components/shared/NavbarComponent.vue'
import Autocomplete from './components/shared/AutocompleteComponent.vue'
import Loading from './components/shared/LoadingComponent.vue'
import Popup from './components/shared/PopupComponent.vue'
import DateNavigation from './components/shared/DateNavigationComponent.vue'
import Menu from './components/shared/MenuComponent.vue'
import Actions from './components/shared/ActionsComponent.vue'
import Buttons from './components/shared/ButtonsComponent.vue'
import InputGroup from './components/shared/InputGroupComponent.vue'
import CheckboxGroup from './components/shared/CheckboxGroupComponent.vue'
// import HomePage from './components/HomePageComponent.vue'
import History from './components/ExerciseHistoryPageComponent.vue'

//Shared components
Vue.component('navbar', Navbar);
Vue.component('autocomplete', Autocomplete);
Vue.component('feedback', require('./components/shared/FeedbackComponent.vue'));
Vue.component('loading', Loading);
Vue.component('popup', Popup);
Vue.component('date-navigation', DateNavigation);
Vue.component('buttons', Buttons);
Vue.component('input-group', InputGroup);
Vue.component('checkbox-group', CheckboxGroup);
Vue.component('nav-menu', Menu);
Vue.component('actions', Actions);
Vue.component('history', History);
// Vue.component('home-page', HomePage);