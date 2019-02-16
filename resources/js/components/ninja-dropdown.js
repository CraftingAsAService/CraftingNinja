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
	props: [ 'title', 'icon', 'placeholder' ],
	template: `
		<div class='post-filter__select ninja-dropdown' v-click-outside='close'>
			<label class='post-filter__label'>
				<i :class='icon + " mr-1"'></i>
				{{ title }}
			</label>
			<div :class='"cs-select cs-skin-border add-filter" + (open ? " cs-active" : "")'>
				<span class='cs-placeholder' v-on:click='open = ! open'>{{ placeholder }}</span>
				<div class='cs-options'>
					<ul>
						<li data-option='' data-value='ilvl' class='cs-selected'>
							<span>
								Item Level
							</span>
						</li>
						<li data-option='' data-value='rarity' class=''>
							<span>
								Rarity
							</span>
						</li>
					</ul>
				</div>
			</div>
		</div>
	`,
	data:function() {
		return {
			open: false
		}
	},
	methods: {
		close:function() {
			this.open = false;
		}
	}
});
