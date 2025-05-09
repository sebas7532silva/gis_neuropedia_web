<?php

/**
 * Adds Pearl_Contact widget.
 */
class Pearl_Contact extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'pearl_contact', // Base ID
			esc_html__( 'Pearl - Contact', 'pearl-medicalguide' ), // Name
			array( 'description' => esc_html__( 'A widget to display contact information.', 'pearl-medicalguide' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}
		?>
		<div class="get-touch">
			<?php
			if ( ! empty( $instance['text'] ) ) {
				echo '<span class="text">' . ( $instance['text'] ) . '</span>';
			}
			?>
			<ul>
				<?php

				if ( ! empty( $instance['address'] ) ) {
					echo '<li><i class="icon-location"></i> <span>' . esc_html( $instance['address'] ) . '</span></li>';
				}

				if ( ! empty( $instance['phone'] ) ) {
					echo '<li><a href="tel:' . esc_attr( $instance['phone'] ) . '"><i class="icon-phone4"></i> <span>' . esc_html( $instance['phone'] ) . '</span></a></li>';
				}

				if ( ! empty( $instance['email'] ) ) {
					echo '<li><a href="mailto:' . esc_attr( $instance['email'] ) . '"><i class="icon-dollar"></i> <span>' . esc_html( $instance['email'] ) . '</span></a></li>';
				}
				?>
			</ul>

		</div>
		<?php
		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 */
	public function form( $instance ) {
		$title   = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Get In Touch', 'pearl-medicalguide' );
		$text    = ! empty( $instance['text'] ) ? $instance['text'] : esc_html__( 'Medical Bibendum auctor, to consequat ipsum, nec sagittis sem.', 'pearl-medicalguide' );
		$address = ! empty( $instance['address'] ) ? $instance['address'] : esc_html__( 'Medical Ltd, Manhattan 1258, New York, USA.', 'pearl-medicalguide' );
		$phone   = ! empty( $instance['phone'] ) ? $instance['phone'] : esc_html__( '(+1) 234 567 8901', 'pearl-medicalguide' );
		$email   = ! empty( $instance['email'] ) ? $instance['email'] : esc_html__( 'robot@pearlthemes.com', 'pearl-medicalguide' );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'pearl-medicalguide' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p><p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>"><?php esc_attr_e( 'Text:', 'pearl-medicalguide' ); ?></label>
			<textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'text' ) ); ?>" cols="30" rows="5"><?php echo( $text ); ?></textarea>
		</p><p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'address' ) ); ?>"><?php esc_attr_e( 'Address:', 'pearl-medicalguide' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'address' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'address' ) ); ?>" type="text" value="<?php echo esc_attr( $address ); ?>">
		</p><p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'phone' ) ); ?>"><?php esc_attr_e( 'Phone:', 'pearl-medicalguide' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'phone' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'phone' ) ); ?>" type="text" value="<?php echo esc_attr( $phone ); ?>">
		</p><p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'email' ) ); ?>"><?php esc_attr_e( 'Email:', 'pearl-medicalguide' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'email' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'email' ) ); ?>" type="text" value="<?php echo esc_attr( $email ); ?>">
		</p>
		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance            = array();
		$instance['title']   = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['text']    = ( ! empty( $new_instance['text'] ) ) ? ( $new_instance['text'] ) : '';
		$instance['address'] = ( ! empty( $new_instance['address'] ) ) ? strip_tags( $new_instance['address'] ) : '';
		$instance['phone']   = ( ! empty( $new_instance['phone'] ) ) ? strip_tags( $new_instance['phone'] ) : '';
		$instance['email']   = ( ! empty( $new_instance['email'] ) ) ? strip_tags( $new_instance['email'] ) : '';

		return $instance;
	}

} // class Pearl_Contact

// register Pearl_Contact widget
function register_pearl_contact() {
	register_widget( 'Pearl_Contact' );
}

add_action( 'widgets_init', 'register_pearl_contact' );