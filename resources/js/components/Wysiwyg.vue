<template>
	<div class="trix-content">

	  <input id="trix" type="hidden" :name="name" :value="value">

	  <trix-editor ref="trix" id="body" input="trix" :placeholder="placeholder"></trix-editor>

	</div>

</template>

<script>
	import Trix from 'trix';
	export default {
		props: ['name', 'value', 'placeholder', 'shouldClear'],

		mounted () {
			// alert(trix);
            this.$refs.trix.addEventListener('trix-change', e => {
            	// console.log('handling');
                this.$emit('input', e.target.innerHTML);
            });
            this.$watch('shouldClear', () => {
                this.$refs.trix.value = '';
            });
		}
	}
</script>