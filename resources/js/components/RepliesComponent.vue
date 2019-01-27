<template>
	<div>
		<div :key="reply.id" v-for="(reply, index) in items">
			<reply :reply="reply" @deleted="remove(index)"></reply>
		</div>

		<paginator :dataSet="dataSet" @changed="fetch"></paginator>

		<p v-if="$parent.locked">
			<i class="fa fa fa-lock ml-5" style="font-size:24px; color:red;"></i> 
			This thread has been locked. No more replies are allowed.
		</p>

		<new-reply v-else @created="add"></new-reply>

	</div>
</template>

<script>
	import Reply from './ReplyComponent.vue';
	import newReply from './NewReply.vue';
	import collection from '../mixins/Collection';

	export default {

		components: { Reply, newReply },

		mixins: [collection],

		data() {
			return {
				dataSet: false,
				locked: this.$parent.locked
			}
		},
		created() {
			this.fetch()
		},
		methods: {
			fetch(page) {
				axios.get(this.url(page)).then(this.refresh);
			},
			url(page) {
				if (! page) {
					let query = location.search.match(/page=(\d+)/);
					page = query ? query[1] : 1;
				}
				return `${location.pathname}/replies?page=${page}`;
			},
			refresh(data) {
				this.dataSet = data.data;
				this.items = data.data.data;

				window.scrollTo(0, 0);
			}
		}
	}
</script>