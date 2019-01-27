<template>
	<button type="" :class="classes" @click="subscribe" v-text="active ? ' Subscribed to this thread' : ' Subscribe to this thread?'"></button>
</template>

<script>
	export default {
		props: ['active'],
		computed: {
			classes() {
				return ['btn', this.active ? 'btn-primary fa fa-bookmark' : 'btn-link fa fa-bookmark-o'];
			}
		},
		methods: {
			subscribe() {

				axios[
					(this.active ? 'delete' : 'post')
				](location.pathname + '/subscriptions');

				this.active = ! this.active;

			},
			unsubscribe() {
				axios.post();
			}
		}
	}
</script>