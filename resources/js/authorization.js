    let user = window.App.user;

	module.exports = {
		updateReply (reply) {
			return reply.user_id === user.id;
		},
		updateThread (thread) {
			return thread.user_id === user.id;
		},
		owns (model, prop = 'user_id') {
			// alert(thread.owner);
			// CHANGED FROM model TO USE thread.owner INSTEAD
			// WHICH IS DEFINED IN THE app HEADER FROM 
			// threads/show.blade
			return thread.owner === user.id;
		},
		isAdmin () {
			return ['Ken Steven', 'JohnDoe', 'Keira Mills'].includes(user.name);
		}
	};

