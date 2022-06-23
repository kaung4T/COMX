jQuery( window ).on( 'elementor:init', function() {
	var ControlMultipleBaseItemView = elementor.modules.controls.BaseMultiple,
	ControlXY_PositionsItemView;

	ControlXY_PositionsItemView = ControlMultipleBaseItemView.extend( {
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
				'click @ui.link': 'onLinkResetXYPositions'
			} );
		},

		defaultXYPositionsValue: {
			'x': 0,
			'y': 0,
		},
		onLinkResetXYPositions: function( event ) {
			event.preventDefault();
			event.stopPropagation();


			this.ui.controls.val('');

			this.updateXYPositionsValue();
		},

		onSlideChange: function( event, ui ) {
			var type = event.currentTarget.dataset.input,
				$input = this.ui.input.filter( '[data-setting="' + type + '"]' );

			$input.val( ui.value );

			//this.setValue( type, ui.value );
			//this.fillEmptyXYPositions();

			this.updateXYPositions();
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
						//_this.updateXYPositions();
					});

				}

			} );

		},
		onReady: function() {
			this.initSliders();
			this.updateXYPositions();
		},

		updateXYPositions: function() {
			this.fillEmptyXYPositions();
			this.updateXYPositionsValue();
		},
		fillEmptyXYPositions: function() {
			var xypositions = this.getPossibleXYPositions(),

				$controls = this.ui.controls,
				$sliders = this.ui.sliders,
				defaultXYPositionsValue = this.defaultXYPositionsValue;

			xypositions.forEach( function( xyposition, index ) {
				var $slider = $sliders.filter( '[data-input="' + xyposition + '"]' );
				var $element = $controls.filter( '[data-setting="' + xyposition + '"]' );

				if ( $element.length && _.isEmpty( $element.val() ) ) {
					$element.val( defaultXYPositionsValue[xyposition] );

					if (elementor.config.version < '2.5') {
						$slider.slider( 'value', defaultXYPositionsValue[xyposition] );
					} else {
						$slider[0].noUiSlider.set( defaultXYPositionsValue[xyposition] );
					}

					//alert(defaultXYPositionsValue[xyposition]);
				}

			} );
		},
		updateXYPositionsValue: function() {
			var currentValue = {},
				xypositions = this.getPossibleXYPositions(),
				$controls = this.ui.controls,
				$sliders = this.ui.sliders,
				defaultXYPositionsValue = this.defaultXYPositionsValue;

			xypositions.forEach( function( xyposition ) {
				var $element = $controls.filter( '[data-setting="' + xyposition + '"]' );

				currentValue[ xyposition ] = $element.length ? $element.val() : defaultXYPositionsValue;

				var $slider = $sliders.filter( '[data-input="' + xyposition + '"]' );

				if (elementor.config.version < '2.5') {
					$slider.slider( 'value', $element.length ? $element.val() : defaultXYPositionsValue );
				} else {
					$slider[0].noUiSlider.set( $element.length ? $element.val() : defaultXYPositionsValue );
				}
			} );
			//alert(currentValue);
			this.setValue( currentValue );
		},

		getPossibleXYPositions: function() {
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

			this.updateXYPositions();
		},
	});
	elementor.addControlView( 'xy_positions', ControlXY_PositionsItemView );
} );
