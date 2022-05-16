<?php
function fcp_custom_js_css() {
		wp_enqueue_script( 'fcp-form-js', plugins_url('/js/fcp-custom.js', dirname(__FILE__)), array( 'jquery' ), '1.38' );
		wp_enqueue_style( 'fcp-form-styles', plugins_url('/css/fcp-style.css',dirname(__FILE__)), array(), '1.40' );

		wp_localize_script( 'fcp-form-js', 'frontend_ajax_object',
			array( 
				'FCP.FCP.heightCentimeter' => __( 'Centimeter', 'fitness-calculators'),
				'FCP.FCP.weightKilogram' => __( 'Kilogram', 'fitness-calculators'),
				'FCP.FCP.heightFeet' => __( 'Feet', 'fitness-calculators'),
				'FCP.FCP.weightPound' => __( 'Pound', 'fitness-calculators'),
				'FCP.FCP.unitLTR' => __( 'Ltr', 'fitness-calculators'),
				'FCP.FCP.unitOz' => __( 'Oz', 'fitness-calculators'),
				'FCP.FCP.unitLBS' => __( 'lbs', 'fitness-calculators'),
				'FCP.FCP.unitGram' => __( 'gram', 'fitness-calculators'),
				'FCP.FCP.bmiUnderweight' => __( 'Underweight', 'fitness-calculators'),
				'FCP.FCP.bmiNormalweight' => __( 'Normal Weight', 'fitness-calculators'),
				'FCP.FCP.bmiOverweight' => __( 'Overweight', 'fitness-calculators'),
				'FCP.FCP.bmiClass1' => __( '(Class I Obese)', 'fitness-calculators'),
				'FCP.FCP.bmiClass2' => __( '(Class II Obese)', 'fitness-calculators'),
				'FCP.FCP.bmiClass3' => __( '(Class III Obese)', 'fitness-calculators'),
				'FCP.FCP.requireField' => __( 'Required Fields', 'fitness-calculators'),
				'FCP.FCP.numberOnly' => __( 'Numbers Only', 'fitness-calculators'),
				'FCP.FCP.positiveNumberOnly' => __( 'Positive Numbers Only', 'fitness-calculators'),
				'FCP.FCP.nonNegativeNumberOnly' => __( 'Non Negative Numbers Only', 'fitness-calculators'),
				'FCP.FCP.integerOnly' => __( 'Integers Only', 'fitness-calculators'),
				'FCP.FCP.positiveIntegerOnly' => __( 'Positive Integers Only', 'fitness-calculators'),
				'FCP.FCP.nonNegativeIntegerOnly' => __( 'Non Negative Integres Only', 'fitness-calculators'),
			)
		);
}
add_action('wp_enqueue_scripts', 'fcp_custom_js_css');