<template>
	
	<button class="pull-right" :class="classes" @click="toggle" title="Love the reply?">
    	<i class="fa fa-heart-o mt-1" style="font-size:24px"></i> 
    	<span v-text="Count"></span>
	</button>


</template>

<script>
	export default {
		props: ['reply'],
		data() {
			return {
				Count: this.reply.favouritesCount,
				isFavourited: this.reply.isFavourited
			}
		},

		computed: {
			classes() {
				return ['p-0 btn btn-xs', this.isFavourited ? 'btn-link' : 'btn-link'];
			},
			endpoint() {
				return '/replies/'+this.reply.id+'/favourites';
			}
		},

		methods: {
			toggle() {
				this.isFavourited ? this.destroy() : this.create();
			},
			create() {
					axios.post(this.endpoint);

					this.isFavourited = true;
					this.Count++;
			},
			destroy() {
					axios.delete(this.endpoint);

					this.isFavourited = false;
					this.Count--;
			},
		}
	}
</script>