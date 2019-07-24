<nav class='shop-pagination' aria-label='Shop navigation' v-if='results.data.length > 0'>
	<ul class='pagination pagination--circle justify-content-center'>
		<li class='page-item' v-if='results.links.prev'>
			<a class='page-link' href='#' @click.prevent='previousPage()'>
				<i class='fa fa-angle-left'></i>
			</a>
		</li>
		<li class='page-item active'>
			<a class='page-link' href='#' @click.prevent v-html='results.meta.current_page'></a>
		</li>
		<li class='page-item' v-if='results.links.next'>
			<a class='page-link' href='#' @click.prevent='nextPage()'>
				<i class='fa fa-angle-right'></i>
			</a>
		</li>
	</ul>
</nav>
