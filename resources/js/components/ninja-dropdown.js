// <ninja-dropdown title='Filter By' icon='fas fa-filter' placeholder='Add Filter'></ninja-dropdown>

Vue.directive('click-outside', {
	bind:function(el, binding, vnode) {
		el.clickOutsideEvent = function(event) {
			// here I check that click was outside the el and his childrens
			if (!(el == event.target || el.contains(event.target))) {
				// and if it did, call method provided in attribute value
				vnode.context[binding.expression](event);
			}
		};
		document.body.addEventListener('click', el.clickOutsideEvent)
	},
	unbind:function(el) {
		document.body.removeEventListener('click', el.clickOutsideEvent)
	},
});

Vue.component('ninja-dropdown', {
	props: [ 'title', 'icon', 'placeholder', 'option', 'options' ],
	template: `
		<div class='post-filter__select ninja-dropdown' v-click-outside='close'>
			<label class='post-filter__label'>
				<i :class='icon + " mr-1"'></i>
				{{ title }}
			</label>
			<div :class='"cs-select cs-skin-border " + (open ? " cs-active" : "")'>
				<span class='cs-placeholder' v-on:click='open = ! open'>{{ placeholder || displayTitle }}</span>
				<ul class='cs-options'>
					<li v-for='optionData in options' v-on:click='optionClick(option, optionData["key"], optionData["title"])'>
						<span>
							<i :class='"fas " + optionData["icon"] + " fa-fw mr-1"'></i>
							{{ optionData["title"] }}
						</span>
					</li>
				</ul>
			</div>
		</div>
	`,
	data:function() {
		return {
			displayTitle: null,
			open: false
		}
	},
	mounted:function() {
		this.displayTitle = this.options[0].title;
	},
	methods: {
		close:function() {
			this.open = false;
		},
		optionClick:function(key, value, title) {
			this.displayTitle = title;
			this.$emit('clicked', key, value)
			this.close();
		}
	}
});
