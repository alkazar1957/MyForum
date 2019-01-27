
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

window.Vue = require('vue');
require('./bootstrap');

let authorizations = require('./authorization.js');

Vue.prototype.authorize = function (...params) {
	if (!window.App.signedIn) return false;
// alert(window.App.signedIn + ' ' + params[1]);
	if (typeof params[0] === 'string') {
		return authorizations[params[0]](params[1]);
	}

	return params[0](window.App.user);
};
Vue.prototype.signedIn = window.App.signedIn;
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))
window.events = new Vue();
Vue.config.ignoredElements = ['trix-editor'];

window.flash = function (message, level = 'success') {
	window.events.$emit('flash', {message, level });
};
Vue.component('flash', require('./components/FlashComponent.vue').default);
// Vue.component('reply-component', require('./components/ReplyComponent.vue').default);
// Vue.component('favourites-component', require('./components/FavouriteComponent.vue').default);

Vue.component('thread-view', require('./pages/ThreadComponent.vue').default);
Vue.component('paginator', require('./components/Paginator.vue').default);
Vue.component('user-notifications', require('./components/UserNotifications.vue').default);
Vue.component('avatar-form', require('./components/AvatarForm.vue').default);
Vue.component('wysiwyg', require('./components/Wysiwyg.vue').default);


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app'
});

