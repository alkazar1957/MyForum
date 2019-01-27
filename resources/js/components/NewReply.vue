<template>
	<div>
		<div v-if="signedIn">
                <div class="form-group">
                    <!-- @csrf -->
                    <label for="body">Reply... </label>

                    <wysiwyg name="body" v-model="body" placeholder="Have something to say?" :shouldClear="completed"></wysiwyg>

                    <button 
                    	type="submit" 
                    	class="btn btn-xs btn-primary float-right"
                    	@click="addReply">
                    	Reply ...
                    </button>
                </div>
                <span class=""></span>
		</div>
                
                <p class="text-center" v-else>Please <a href="/login">Sign-in</a> to participate in this discussion.</p>
	</div>
</template>

<script>
	import 'jquery.caret';
	import 'at.js';

	export default {

		data() {
			return {
				body: '',
				completed: false
			};
		},

 		mounted() {
 			$('#body').atwho({
 				at: '@',
 				delay: 700,
 				callbacks: {
 					remoteFilter: function(query, callback) {
 						// console.log('called');
 						$.getJSON("/api/users", {username: query}, function(usernames) {
 							callback(usernames)
 						});
 					}
 				}
 			});
 		},
		methods: {
			addReply() {
				axios.post(location.pathname + '/replies', { body: this.body } )
                    .catch(error => {
                        flash(error.response.data, 'danger');
                    })
					.then( ({data}) => {
						this.body = '';
						this.completed = true;

						flash('Your reply has been posted');

						// this.$refs.trix.$refs.trix.value = '';

						this.$emit('created', data);
					});
			}
		}
	}
</script>