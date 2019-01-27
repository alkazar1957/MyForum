<template>
	<div class="">
        <div class="align-self-end mr-2 pull-right">
            <a :href="'/profiles/'+user.name" class="btn btn-xs btn-warning">Edit</a>
        </div>

        <div class="">
    	    <img :src="avatar" id="profile_picture" class="rounded-circle w-25">
	    </div>

        <div v-text="user.name" class="card card-header mt-2"></div>

        <div v-text="'Username: ' + user.username" class="card card-header mt-2">UserName: </div>

	    <form v-if="canUpdate" method="post" enctype="multipart/form-data">
	        <input type="file" name="avatar" accept="image/*" @change="onChange">
	        <button type="submit" class="btn btn-sm btn-primary">Save Avatar</button>
	    </form>

	</div>
</template>

<script>
	export default {
		props: ['user'],

		data() {
			return {
				avatar: '/profiles/showAvatar/'+this.user.name
			}
		},

		computed: {
			canUpdate() {
				return this.authorize(user => user.id === this.user.id)
			}
		},

		methods: {
			onChange(e) {
				if (! e.target.files.length) return;
				let avatar = e.target.files[0];

				let reader = new FileReader();

				reader.readAsDataURL(avatar);

				reader.onload = e => {
					this.avatar = e.target.result;
				};

				this.persist(avatar);
			},
			persist(avatar) {
				let data = new FormData();
				data.append('avatar', avatar);
				axios.post('/profiles/avatar/'+this.user.name, data)
					.then(() => flash('Avatar Uploaded!'));
			}
		}
	}
</script>