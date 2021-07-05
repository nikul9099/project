<form action="" method="POST">
<input type="hidden" name='product_rules' value='1'>
<?php wp_nonce_field( 'wcol_save_rules', '_wcol_save_rules_nonce', true ); 
?>
<div class="wcol-rules-section wcol-products-section">
	<?php if($wcol_settings['enable_product_limit']!='on'){
		$style="style='pointer-events: none;opacity:0.5;'";
		echo '<span style="color:red"><strong>'.esc_html__('Note! ', 'xsollwc-domain').'</strong>'.esc_html__('Product Limits are Disabled.','xsollwc-domain').'</span>';
		?><div class="wcol-help-tip" style="float:none; margin-right:0;">
			<span class="wcol-tip" > <?php esc_html_e('Product Limits are disabled, You can enable Product Limits in Advance Tab.'); ?> </span>
		</div><?php
	}else{
		$style="";
	} ?>
	<table class="wp-list-table widefat fixed striped" <?php echo $style; ?>>
		<thead>
			<tr>
				<th class="manage-column column-cb check-column wcol-select-all">
					<input type="checkbox"/>
				</th>
				<th class="manage-column column-primary wcol-object-type-th">
					<?php esc_html_e('Product', 'xsollwc-domain'); ?>
				</th>
				<th style="" class="manage-column">	
					<?php esc_html_e('Minimum Limit', 'xsollwc-domain'); ?>
				</th>
				<!--<th style="width:11%"><?php esc_html_e('Maximum Limit', 'xsollwc-domain'); ?></th>-->
				<th style="" class="manage-column">
					<?php esc_html_e('Applied on', 'xsollwc-domain'); ?>
					<div class="wcol-help-tip" style="float:none; margin-right:0;">
						<span class="wcol-tip" > <?php esc_html_e("Select wether Min and Max limits will be applied on Product(s)'s Amount in cart or on Quantity of Product(s)'s Items in Cart.", 'xsollwc-domain'); ?> </span>
					</div>
				</th>
				<th style="" class="manage-column">
					<?php esc_html_e('Accumulative', 'xsollwc-domain'); ?>
					<div class="wcol-help-tip" style="float:none; margin-right:0;">
						<span class="wcol-tip" > <?php esc_html_e('Either limits will be applied accomulatively or individually on selected Product categories. i.e if you check this box then accomulative total amount or quantity for selected Products will be considered rather than individual Product.'); ?> </span>
					</div>
				</th>
				<th class="manage-column"><?php esc_html_e('More Options', 'xsollwc-domain'); ?></th>
			</tr>
		</thead>
		<tbody class="wcol-main-body">
			<?php 
			$xs_i = 0;
			if(is_array($wcol_product_rules)){
				foreach($wcol_product_rules as $rule){ ?>
				<tr data-id='<?php echo $rule['rule-id'] ?>'>
					<td class="check-column wcol-cb">
						<input type="hidden" name="wcol-rules[product-rules][rule-id][<?php echo $xs_i; ?>]" value="<?php echo $rule['rule-id']; ?>"/>
						<input type="checkbox"/>
					</td>
					<td class="column-primary">
						<select class="wcol-select-products <?php if($rule['disable-limit']=='on'){echo 'wcol-disabled';} ?>" name="wcol-rules[product-rules][object-ids][<?php echo $xs_i; ?>][]" multiple="multiple">
						<?php if(is_array($rule["object_ids"])){
							foreach($rule["object_ids"] as $product_id){
								    if(isset($product_id)&& !empty($product_id) ){	    	
								    	if($product_id == "-1"){
							?>
											<option value="<?php echo esc_html($product_id)?>" selected="selected"> 
												<?php  esc_html_e("All Products" , 'xsollwc-domain') ?>
											</option>
							<?php
								    	}else{
											$product = wc_get_product( $product_id );
											if($product != null){
							?>
												<option value="<?php echo  esc_html($product_id)?>" selected="selected"> 
													<?php  echo get_the_title( $product_id ); ?>
												</option>
			 				<?php 			}
			 							}
						            }	
							}
						} 
						?>
						</select>
						<button type="button" class="toggle-row"><span class="screen-reader-text"><?php esc_html_e( 'Show more details', 'xsollwc-domain') ?></span></button>
					</td>
					<td data-colname="<?php esc_attr_e('Minimum Limit', 'xsollwc-domain');?>">
						<input type="number" min="0" class="wcol-rule-min-limit <?php if($rule['disable-limit']=='on'){echo 'wcol-disabled';} ?>" name="wcol-rules[product-rules][rule-limit][<?php echo $xs_i; ?>]" value="<?php echo $rule['wcol_min_order_limit']; ?>" />
					</td>
					
					<td data-colname="<?php esc_attr_e('Applied On', 'xsollwc-domain');?>">
						<?php 
							$applied_on_options = '' ;
							$applied_on_options .= '<option value="amount" ';
							$applied_on_options .= ($rule["wcol_applied_on"]=="amount")? "selected":"";
							$applied_on_options .= '>' . esc_html__('Amount', 'xsollwc-domain') . '</option>';
							$applied_on_options .= '<option value="quantity" ';
							$applied_on_options .= ($rule["wcol_applied_on"]=="quantity")? "selected":"";
							$applied_on_options .=' >' . esc_html__('Quantity', 'xsollwc-domain') . '</option>';
							$applied_on_options  = apply_filters('wcol_applied_on_options',$applied_on_options,$rule);
						?>
						<select class="wcol-select-applied-on <?php if($rule['disable-limit']=='on'){echo 'wcol-disabled';} ?>" name="wcol-rules[product-rules][applied-on][<?php echo $xs_i; ?>]" >
							<?php echo $applied_on_options;  ?>
						</select>
					</td>
					<td data-colname="<?php esc_attr_e('Accumulative', 'xsollwc-domain');?>">
						<input type="hidden" class="wcol-loop-checkbox-hidden" name="wcol-rules[product-rules][accomulative][<?php echo $xs_i; ?>]" value="<?php echo $rule['accomulative']; ?>"/>
						<input type="checkbox" class="wcol-accomulative wcol-loop-checkbox <?php if($rule['disable-limit']=='on'){echo 'wcol-disabled';} ?>" <?php if($rule['accomulative']=='on'){ echo 'checked';} ?> />
					</td>
					<td class="wcol-more-options-td">
						<div class="wcol-more-options">
							<a class="wcol-show-more-options" href="#"><?php if($rule['disable-limit']=='on'){esc_html_e('Enable this Limit', 'xsollwc-domain');}else{esc_html_e('More Options', 'xsollwc-domain');} ?></a>
							<a class="wcol-hide-more-options wcol-hidden" href="#"><?php esc_html_e('Hide Options', 'xsollwc-domain'); ?></a>
							<div class="wcol-options-open wcol-hidden"></div>
							
							<div class=" wcol-rule-options wcol-hidden">
								<div class="wcol-more-options-header">
									<h3><?php esc_html_e('More Options' , 'xsollwc-domain'); ?></h3>
								</div>
								<table class="">
									<tr>
										<th><?php esc_html_e('Disable', 'xsollwc-domain'); ?>:</th>
										<td>
											<input type="hidden" class="wcol-loop-checkbox-hidden" name="wcol-rules[product-rules][disable-limit][<?php echo $xs_i; ?>]" value="<?php echo $rule['disable-limit']; ?>"/>
											<input class="wcol-disable-rule-limit wcol-loop-checkbox" type="checkbox" <?php if($rule['disable-limit']=='on'){echo "checked";} ?> />
										</td>
									</tr>
									<tr>
										<th><?php esc_html_e('Enable Maximum Limit' , 'xsollwc-domain'); ?>:</th>
										<td>
											<input type="hidden" class="enable-max-rule-limit-hidden wcol-loop-checkbox-hidden" name="wcol-rules[product-rules][enable-max-rule-limit][<?php echo $xs_i; ?>]" value="<?php echo $rule['enable-max-rule-limit']; ?>" />
											<input class="enable-max-rule-limit wcol-loop-checkbox  <?php if($rule['disable-limit']=='on'){echo 'wcol-disabled';} ?>" type="checkbox" <?php if($rule['enable-max-rule-limit']=='on'){echo "checked";} ?> />
										</td>
									</tr>
									
									<tr class=" <?php if($rule['enable-max-rule-limit']!='on'){echo "wcol-hidden";} ?>">
										<th><?php esc_html_e('Maximum Limit' , 'xsollwc-domain'); ?>:</th>
										<td><input type="number" min="0" class="wcol-rule-max-limit <?php if($rule['disable-limit']=='on'){echo 'wcol-disabled';} ?>" name="wcol-rules[product-rules][max-rule-limit][<?php echo $xs_i; ?>]" value="<?php echo $rule['wcol_max_order_limit']; ?>"/></td>
									</tr>
								</table>
							</div>
						</div>
					</td>
				</tr>
				<?php
				$xs_i++;
				} 
			}
			?>
			<input type="hidden" class="xswcol-pid" value="<?php echo $xs_i; ?>" />
		</tbody>
		
	</table>
	<div class="wcol-actions-btn">
		<input type="submit" class="button button-primary button-large xs-wcol" value="<?php esc_html_e('Save', 'xsollwc-domain')?>"/>
		<input type="button" class="button button-large wcol-delete-selected" value="<?php esc_html_e('Delete Selected' , 'xsollwc-domain'); ?>"/>
		<input type="button" id="wcol-add-product-rule" class="button button-primary button-large" value="<?php esc_html_e('Add New Rule' , 'xsollwc-domain'); ?>"/>
		<span class="spinner wcol_spinner"></span>
	</div>
</div>
</form>