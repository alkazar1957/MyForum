<template>

    <li class="dropdown" v-if="notifications.length">

        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">

            <i class="fa fa-bell" :style="notifications ? 'color:red' : ''"></i>

        </a>

        <ul class="dropdown-menu truncate">

            <li v-for="notification in notifications" >

                <a :href="notification.data.link"

                   v-text="notification.data.message"

                   @click="markAsRead(notification)"

                ></a>
                <hr class="m-1">

            </li>

        </ul>

    </li>
</template>

<script>
	export default {
		data() {
			return { notifications: false }
		},
		created() {
			axios.get('/profiles/' + window.App.user.name + '/notifications')
				.then(response => this.notifications = response.data);
		},
		methods: {
			markAsRead(notification) {
				axios.delete('/profiles/' + window.App.user.name + '/notifications/' + notification.id)
			}
		}
	}
</script>