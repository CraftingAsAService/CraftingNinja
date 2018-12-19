'use strict';

caas.myLists = new Vue({
	el: '.my-lists',
	data: function() {
		return {
			results: [],
			noResults: false
		}
	},
	mounted: function() {
		axios
			.get('/api/users/my/lists')
			.then(response => {
				this.results = response.data;
				this.noResults = response.data.length == 0;
			})
			.catch(error => console.log(error));
	},
	methods: {

	}
});
