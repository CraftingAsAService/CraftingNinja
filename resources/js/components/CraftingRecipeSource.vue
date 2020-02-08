<template>
	<img :src='src' alt='' data-toggle='tooltip' data-html='true' :data-title='job.abbreviation' :style='jobMatches ? "" : "opacity: .5; cursor: pointer;"' @click='switchJob()' height='24' width='24' >
</template>

<script>
	import { getters, mutations } from '../stores/crafting';

	export default {
		props: [ 'sectionJobId', 'jobId', 'recipeId', 'tierId' ],
		computed: {
			...getters,
			jobMatches() {
				return this.sectionJobId == this.jobId;
			},
			job() {
				return this.jobData[this.jobId];
			},
			src() {
				return '/assets/' + game.slug + '/jobs/' + this.job.icon + '.png';
			}
		},
		methods: {
			switchJob() {
				if (this.jobMatches)
					return;

				// mutations.setItemZonePreference(this.itemId, this.jobId);

				// this.$eventBus.$emit('zoneRefresh', this.jobId);
				// this.$eventBus.$emit('zoneRefresh', this.sectionJobId);
				this.$eventBus.$emit('craftRefresh');
			}
		}
	}
</script>
