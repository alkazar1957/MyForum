<template>
<div :id="'reply-'+id" :class="isBest ? 'best-reply' : ''">

		<div v-if="editing">
			<wysiwyg v-model="body"></wysiwyg>
                <div class="col-sm-12 mb-4">
	                <button type="" class="p-1 btn btn-sm btn-link pull-right" @click="update">Update</button>
	                <button type="button" class="p-1 btn btn-sm btn-link pull-right" @click="cancel">Cancel</button>
                </div>
        </div>

        <div v-else class="row p-0">
        	<div class="col-sm-2 m-0 p-2">
		        <a :href="'/profiles/'+reply.owner.username"
		        	 class="small">
        	<img :src="'/profiles/showAvatar/'+reply.owner.username" width="30px" id="profile_picture" class="bg-info rounded-circle m-auto">
        	<br>
        	<span v-text="reply.owner.username"></span>
		        </a>
    		</div>

	    	<div class="col-sm-10 p-2 reply-body">
		        <a :href="'/profiles/'+reply.owner.username"
		        	v-text="reply.owner.username">
		        </a> Said... 
	        	<span v-html="body" class=""></span>
	        	<div>
	                <favourite :reply="reply"></favourite>
		        	<span v-if="authorize('updateReply', reply) && ! locked">
			            <button type="button" class="p-1 btn btn-xs btn-link pull-right" @click="editing = true" title="Edit Reply">
			            	<i class="fa fa-edit" style="font-size:24px"></i>
			        	</button>
			            <button type="button" class="p-1 btn btn-xs btn-link pull-right" @click="destroy" title="Delete Reply">
			            	<i class="fa fa-trash" style="font-size:24px"> </i></button>
			        </span>
			        <span v-if="authorize('owns', reply.thread_id)">
			            <button type="button" class="p-1 btn btn-xs btn-link pull-right" @click="markBestReply" title="Best Reply?" v-show="! isBest">
			            	<i class="fa fa-check-circle-o" style="font-size:24px"></i>
			            </button>
			        </span>
			        <span class="pull-right mt-1 mr-2 bg-warning" 
			        	v-html="isBest ? '<i class=\'fa far fa-check\'></i> Best Reply.' : ''" >
		        	</span>
	        	</div>
			</div>
		</div>
</div>
</template>

<script>

	import Favourite from './FavouriteComponent.vue';
	import moment from 'moment';

	export default {
		props: ['reply', 'locked'],

		components: {Favourite},
		data() {
			return {
				editing: false,
				id: this.reply.id,
				body: this.reply.body,
				// reply: this.reply,
				thread: window.thread
			};
		},
		computed: {
			ago() {
				return moment(this.reply.created_at).fromNow();
			},
			threadOwner() {
				return window.thread.owner;
			},
			isBest(){
				return this.thread.best_reply_id == this.id;
			},
			userId() {
				return window.App.user.id;
			}
		},
		methods: {
			update() {
                axios.patch(
                    '/replies/' + this.id, {
                        body: this.body
                    })
                    .catch(error => {
                        flash(error.response.data, 'danger');
                    });

			this.editing = false;

			flash('Updated');
			},
			cancel() {
				this.editing = false;

				this.body = this.reply.body;
			},
			destroy() {
				axios.delete('/replies/' + this.id);

				this.$emit('deleted', this.id);
				// $(this.$el).fadeOut(300);

				// flash('Your reply has been deleted');
			},
			markBestReply() {
				axios.post('/replies/'+ this.id +'/best');

				this.thread.best_reply_id = this.id;

				// window.events.$emit('best-reply-selected', this.reply.id);
			}
		}
	}
</script>