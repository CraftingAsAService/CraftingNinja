// https://stackoverflow.com/a/42461598/286467
Vue.directive('tooltip', function(el, binding) {
	$(el).tooltip({
		 title: binding.value,
		 placement: binding.arg,
		 trigger: 'hover'
	 });
});
