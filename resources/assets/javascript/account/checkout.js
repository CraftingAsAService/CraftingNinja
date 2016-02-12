var checkout = {
	stripe_handler: null,
	init:function() {
		checkout.setup();
		checkout.events();
	},
	setup:function() {
		checkout.stripe_handler = StripeCheckout.configure({
			key: $('meta[name=stripe-token]').attr('content'),
			image: '/images/logo-128.png',
			local: 'auto',
			token: checkout.actions.stripe_token
		});
	},
	events:function() {
		$('input[name=amount]').keydown(checkout.actions.amount_input_keydown);
		$('.checkout').click(checkout.actions.open_stripe);
		$(window).on('popstate', checkout.actions.page_navigation);
	},
	actions: {
		stripe_token:function(token) {
			$('#checkout-form')
				.find('input[name=token]').val(token.id)
				.end().submit();
		},
		amount_input_keydown:function(event) {
			if (event.which == 13)
			{
				event.preventDefault();
				checkout.actions.open_stripe();
				return false;
			}
		},
		open_stripe:function(event) {
			if (typeof event !== 'undefined')
				event.preventDefault();

			var amount = Math.ceil(parseFloat($('input[name=amount]').val()));

			if (isNaN(amount) || amount < 1)
				amount = 20;

			$('input[name=final-amount]').val(amount);

			checkout.stripe_handler.open({
				name: 'Crafting as a Service',
				description: "Advanced Crafter Status",
				bitcoin: true,
				email: $('input[name=email]').val(),
				amount: amount * 100 // $20 should be inserted as 2000
			});
		},
		page_navigation:function() {
			checkout.stripe_handler.close();
		}
	}
}

$(checkout.init);