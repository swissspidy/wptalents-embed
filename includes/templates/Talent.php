<?php

namespace WPTalents_Embed\Templates;

use WPTalents_Embed\API\Helper;

/**
 * Class Talent
 *
 * @package WPTalents_Embed\Templates
 */
class Talent {

	protected $talent_id;

	protected $html;

	public function __construct( $id ) {
		$this->talent_id = $id;

		$html = get_transient( 'wptalents_embed_talent_' . $this->talent_id );

		if ( ! $html || '' === $html ) {
			$this->populate_data();
		} else {
			$this->html = $html;
		}
	}

	protected function populate_data() {
		$talent = Helper::get_talent_by_id( $this->talent_id );

		$this->html = '';

		if ( ! $talent ) {
			return;
		}

		switch ( $talent->type ) {
			default:
			case 'company':
				$itemtype = 'Corporation';
				break;
			case 'person':
				$itemtype = 'Person';
				break;
		}

		ob_start();
		?>

		<div class="wptalents-talent wptalents-talent--<?php echo esc_attr( $talent->type ); ?> wptalents-talent--<?php echo esc_attr( esc_attr( $talent->slug ) ); ?>"
		     data-wptalents_id="<?php echo absint( $talent->ID ); ?>"
		     itemscope itemtype="http://schema.org/<?php echo esc_attr( $itemtype ); ?>">
			<div class="wptalents-talent__summary">
				<a class="wptalents-talent__avatar"
				   itemprop="url"
				   target="_blank"
				   title="<?php echo esc_attr( $talent->title ); ?>"
				   href="<?php echo esc_url( $talent->link ); ?>">
					<img alt="<?php echo esc_attr( $talent->title ); ?>"
					     src="<?php echo esc_url( $talent->avatar ); ?>"
					     width="90"
					     height="90"
					     itemprop="<?php echo ( $talent->type === 'company' ) ? 'logo' : 'image'; ?>"
						/>
				</a>

				<div class="wptalents-talent__header">
					<h3 class="wptalents-talent__title" itemprop="name">
						<a href="<?php echo esc_url( $talent->link ); ?>"
						   itemprop="url"
						   target="_blank"
						   title="<?php echo esc_attr( $talent->title ); ?>">
							<?php echo esc_html( $talent->title ); ?>
						</a>
					</h3>
					<?php if ( ! empty( $talent->byline ) ) { ?>
						<span class="wptalents-talent__byline" itemprop="description">
							<?php echo esc_html( $talent->byline ); ?>
						</span>
					<?php } ?>

					<ul class="wptalents-talent__meta">
						<?php if ( isset( $talent->job ) && ! empty( $talent->job ) ) { ?>
							<li class="wptalents-talent__meta_item" itemprop="jobTitle">
								<?php echo esc_html( $talent->job ); ?>
							</li>
						<?php } ?>
						<?php if ( isset( $talent->location ) ) { ?>
							<li
								class="wptalents-talent__meta_item"
								itemprop="<?php echo esc_attr( ( 'company' === $talent->type ) ? 'location' : 'homeLocation' ); ?>"
								itemscope itemtype="http://schema.org/Place">
								<span itemprop="name"><?php echo esc_html( strtok( $talent->location->name, ',' ) ); ?></span>

								<div itemprop="geo" itemscope itemtype="http://schema.org/GeoCoordinates">
									<meta itemprop="latitude" content="<?php echo esc_attr( $talent->location->lat ); ?>" />
									<meta itemprop="longitude" content="<?php echo esc_attr( $talent->location->long ); ?>" />
								</div>
							</li>
						<?php } ?>
						<?php if ( 'company' === $talent->type && true == $talent->wordpress_vip ) { ?>
							<li class="wptalents-talent__meta_item">
								<?php echo esc_html__( 'WordPress.com VIP Partner', 'wptalents-embed' ); ?>
							</li>
						<?php } ?>
						<?php if ( isset( $talent->plugins ) && 0 < count( $talent->plugins ) ) { ?>
							<li class="wptalents-talent__meta_item">
								<?php printf(
									_n( '%s Plugin', '%s Plugins', count( $talent->plugins ), 'wptalents-embed' ),
									count( $talent->plugins )
								); ?>
							</li>
						<?php } ?>
						<?php if ( isset( $talent->themes ) && 0 < count( $talent->themes ) ) { ?>
							<li class="wptalents-talent__meta_item">
								<?php printf(
									_n( '%s Theme', '%s Themes', count( $talent->themes ), 'wptalents-embed' ),
									count( $talent->themes )
								); ?>
							</li>
						<?php } ?>
					</ul>
				</div>

				<a class="wptalents_talent__follow"
				   href="<?php echo esc_url( $talent->link ); ?>"
				   itemprop="url"
				   target="_blank"
				   title="<?php echo esc_attr( sprintf( __( 'Follow %s on WP Talents', 'wptalents-embed' ), $talent->title ) ); ?>"
					>
					<?php echo esc_html__( 'Follow on WP Talents', 'wptalents-embed' ); ?>
				</a>
			</div>

			<?php if ( ! empty( $talent->excerpt ) ) { ?>
				<div class="wptalents-talent__detail"><?php echo $talent->excerpt; ?></div>
			<?php } ?>

			<?php if ( isset( $talent->team ) && ! empty( $talent->team ) ) { ?>
				<div class="wptalents-talent__team">
					<?php
					$team_count = 0;
					foreach ( $talent->team as $person ) {
						$team_count ++;

						if ( 4 == $team_count ) { ?>
							<div class="wptalents-talent wptalents-talent__team_more">
								<?php printf(
									__( '%s more team members', 'wptalents-embed' ),
									count( $talent->team ) - 3
								); ?>
							</div>
							<?php
							break;
						}
						?>
						<div class="wptalents-talent wptalents-talent--<?php echo esc_attr( $person->type ); ?> wptalents-talent--<?php echo esc_attr( esc_attr( $person->slug ) ); ?>"
						     data-wptalents_id="<?php echo absint( $person->ID ); ?>"
						     itemscope itemtype="http://schema.org/Person">
							<div class="wptalents-talent__summary">
								<a class="wptalents-talent__avatar"
								   itemprop="url"
								   target="_blank"
								   title="<?php echo esc_attr( $person->title ); ?>"
								   href="<?php echo esc_url( $person->link ); ?>">
									<img alt="<?php echo esc_attr( $person->title ); ?>"
									     src="<?php echo esc_url( $person->avatar ); ?>"
									     width="45"
									     height="45"
									     itemprop="image"
										/>
								</a>

								<div class="wptalents-talent__header">
									<h4 class="wptalents-talent__title" itemprop="name">
										<a href="<?php echo esc_url( $person->link ); ?>"
										   itemprop="url"
										   target="_blank"
										   title="<?php echo esc_attr( $person->title ); ?>">
											<?php echo esc_html( $person->title ); ?>
										</a>
									</h4>
									<?php if ( ! empty( $person->job ) ) { ?>
										<span class="wptalents-talent__byline" itemprop="description">
											<?php echo esc_html( $person->job ); ?>
										</span>
									<?php } ?>
								</div>
							</div>
						</div>
					<?php } ?>
				</div>
			<?php } ?>

			<?php if ( isset( $talent->jobs ) && ! empty( $talent->jobs ) ) { ?>
				<div class="wptalents-talent__jobs">
					<h5 class="wptalents-talent__jobs_title"><?php printf( __( '%s is hiring:', 'wptalents-embed' ), $talent->title ); ?></h5>
					<ul class="wptalents-talent__jobs_list">
						<?php
						$jobs_count = 0;
						foreach ( $talent->jobs as $job ) {
							$jobs_count ++;

							if ( 4 == $jobs_count ) {
								break;
							}
							?>
							<li class="wptalents-job">
								<a class="wptalents-job__title"
								   itemprop="url"
								   target="_blank"
								   title="<?php echo esc_attr( $job->title ); ?>"
								   href="<?php echo esc_url( $job->link ); ?>">
									<?php echo esc_html( $job->title ); ?>
								</a>
							</li>
						<?php } ?>
					</ul>
				</div>
			<?php } ?>
		</div>

		<?php
		$this->html = ob_get_clean();

		//set_transient( 'wptalents_embed_talent_' . $this->talent_id, $this->html, WEEK_IN_SECONDS );
		set_transient( 'wptalents_embed_talent_' . $this->talent_id, $this->html, HOUR_IN_SECONDS );
	}

	public function render() {
		if ( isset( $this->html ) ) {
			return $this->html;
		}

		return '';
	}

} 