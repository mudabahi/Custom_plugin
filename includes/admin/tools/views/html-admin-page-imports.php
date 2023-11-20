<?php
/**
 * Admin View: Imports
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div id="poststuff" class="give-clearfix">
	<div id="give-dashboard-widgets-wrap">
		<div id="post-body">
			<div id="post-body-content">

				<?php
				/**
				 * Fires before the reports Import tab.
				 *
				 * @since 1.8.14
				 */
				do_action( 'give_tools_tab_import_content_top' );
				?>

				<table class="widefat Import-options-table give-table">
					<thead>
					<tr>
						<th scope="col"><?php esc_html_e( 'Importieren Type', 'give' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Importieren Options', 'give' ); ?></th>
					</tr>
					</thead>
					<tbody>
					<?php
					/**
					 * Fires in the reports Import tab.
					 *
					 * Allows you to add new TR elements to the table before
					 * other elements.
					 *
					 * @since 1.8.14
					 */
					do_action( 'give_tools_tab_import_table_top' );
					?>

					<tr class="give-Import-pdf-sales-earnings">
						<td scope="row" class="row-title">
							<h3>
								<span><?php esc_html_e( 'Spenden importieren', 'give' ); ?></span>
							</h3>
							<p><?php esc_html_e( 'Importieren Sie eine CSV-Datei mit Spenden.', 'give' ); ?></p>
						</td>
						<td>
							<a class="button" href="<?php echo esc_url( add_query_arg( array( 'importer-type' => 'import_donations' ) ) ); ?>">
								<?php esc_html_e( 'CSV importieren', 'give' ); ?>
							</a>
						</td>
					</tr>

					<tr class="give-import-core-settings">
						<td scope="row" class="row-title">
							<h3>
								<span><?php esc_html_e( 'Importieren Sie die besten Spendeneinstellungen', 'give' ); ?></span>
							</h3>
							<p><?php esc_html_e( 'Importieren Sie die Einstellungen für die beste Spende im JSON-Format.', 'give' ); ?></p>
						</td>
						<td>
							<a class="button" href="<?php echo esc_url( add_query_arg( array( 'importer-type' => 'import_core_setting' ) ) ); ?>">
								<?php esc_html_e( 'JSON importieren', 'give' ); ?>
							</a>
						</td>
					</tr>

					<?php
					/**
					 * Fires in the reports Import tab.
					 *
					 * Allows you to add new TR elements to the table after
					 * other elements.
					 *
					 * @since 1.8.14
					 */
					do_action( 'give_tools_tab_import_table_bottom' );
					?>
					</tbody>
				</table>

				<?php
				/**
				 * Fires after the reports Import tab.
				 *
				 * @since 1.8.14
				 */
				do_action( 'give_tools_tab_import_content_bottom' );
				?>

			</div>
			<!-- .post-body-content -->
		</div>
		<!-- .post-body -->
	</div><!-- #give-dashboard-widgets-wrap -->
</div><!-- #poststuff -->
