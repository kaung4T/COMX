jQuery( window ).on( 'elementor:init', function() {
	var ControlMultipleBaseItemView = elementor.modules.controls.BaseMultiple,
	ControlXY_MovementItemView;

	ControlXY_MovementItemView = ControlMultipleBaseItemView.extend( {
		ui: function() {
			var ui = ControlMultipleBaseItemView.prototype.ui.apply( this, arguments );
			ui.controls = '.elementor-slider-input > input:enabled';
			ui.sliders = '.elementor-slider';
			ui.link = 'button.reset-controls';
			//ui.colors = '.elementor-shadow-color-picker';

			return ui;
		},
		events: function() {
			return _.extend( ControlMultipleBaseItemView.prototype.events.apply( this, arguments ), {
				'slide @ui.sliders': 'onSlideChange',
				'click @ui.link': 'onLinkResetXYMovement'
			} );
		},

		defaultXYMovementValue: {
			'x': 0,
			'y': 0,
		},
		onLinkResetXYMovement: function( event ) {
			event.preventDefault();
			event.stopPropagation();


			this.ui.controls.val('');

			this.updateXYMovementValue();
		},

		onSlideChange: function( event, ui ) {
			var type = event.currentTarget.dataset.input,
				$input = this.ui.input.filter( '[data-setting="' + type + '"]' );

			$input.val( ui.value );

			//this.setValue( type, ui.value );
			//this.fillEmptyXYMovement();

			this.updateXYMovement();
		},
		/*onBeforeDestroy: function() {

			this.$el.remove();
		}*/
		initSliders: function() {
			var _this = this;
			var value = this.getControlValue();

			this.ui.sliders.each( function(index, slider) {
				var $slider = jQuery( this ),
					$input = $slider.next( '.elementor-slider-input' ).find( 'input' );

					if (elementor.config.version < '2.5') {
						$slider.slider( {
							value: value[ this.dataset.input ],
							min: +$input.attr( 'min' ),
							max: +$input.attr( 'max' ),
							step: +$input.attr( 'step' )
						} );
					} else {
						var sliderInstance = noUiSlider.create(slider, {
							start: [value[slider.dataset.input]],
							step: 1,
							range: {
								min: +$input.attr('min'),
								max: +$input.attr('max')
							},
							format: {
								to: function to(sliderValue) {
									return +sliderValue.toFixed(1);
								},
								from: function from(sliderValue) {
									return +sliderValue;
								}
							}
						});


					sliderInstance.on('slide', function (values) {
						var type = sliderInstance.target.dataset.input;

						$input.val(values[0]);

						_this.setValue(type, values[0]);
						//_this.updateXYMovement();
					});

				}

			} );

		},
		onReady: function() {
			this.initSliders();
			this.updateXYMovement();
		},

		updateXYMovement: function() {
			this.fillEmptyXYMovement();
			this.updateXYMovementValue();
		},
		fillEmptyXYMovement: function() {
			var xymovement = this.getPossibleXYMovement(),

				$controls = this.ui.controls,
				$sliders = this.ui.sliders,
				defaultXYMovementValue = this.defaultXYMovementValue;

			xymovement.forEach( function( xymovement, index ) {
				var $slider = $sliders.filter( '[data-input="' + xymovement + '"]' );
				var $element = $controls.filter( '[data-setting="' + xymovement + '"]' );

				if ( $element.length && _.isEmpty( $element.val() ) ) {
					$element.val( defaultXYMovementValue[xymovement] );

					if (elementor.config.version < '2.5') {
						$slider.slider( 'value', defaultXYMovementValue[xymovement] );
					} else {
						$slider[0].noUiSlider.set( defaultXYMovementValue[xymovement] );
					}

					//alert(defaultXYMovementValue[xymovement]);
				}

			} );
		},
		updateXYMovementValue: function() {
			var currentValue = {},
				xymovements = this.getPossibleXYMovement(),
				$controls = this.ui.controls,
				$sliders = this.ui.sliders,
				defaultXYMovementValue = this.defaultXYMovementValue;

			xymovements.forEach( function( xymovement ) {
				var $element = $controls.filter( '[data-setting="' + xymovement + '"]' );

				

				var $slider = $sliders.filter( '[data-input="' + xymovement + '"]' );

				if (elementor.config.version < '2.5') {
					$slider.slider( 'value', $element.length ? $element.val() : defaultXYMovementValue );
				} else {
					$slider[0].noUiSlider.set( $element.length ? $element.val() : defaultXYMovementValue );
				}

				currentValue[ xymovement ] = $element.length ? $element.val() : defaultXYMovementValue;

			} );
			//
			//alert(currentValue['x']+' '+currentValue['x']);
			//console.log(currentValue);
			this.setValue( currentValue );
		},

		getPossibleXYMovement: function() {
			return [
				'x',
				'y',
			];
		},
		onInputChange: function( event ) {
			var inputSetting = event.target.dataset.setting;

			var type = event.currentTarget.dataset.setting,
			$slider = this.ui.sliders.filter( '[data-input="' + type + '"]' );

			if (elementor.config.version < '2.5') {
				$slider.slider( 'value', this.getControlValue( type ) );
			} else {
				$slider[0].noUiSlider.set( this.getControlValue( type ) );
			}

			this.updateXYMovement();
		},
	});
	elementor.addControlView( 'xy_movement', ControlXY_MovementItemView );
} );
